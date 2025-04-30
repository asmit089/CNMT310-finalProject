<?php

// running session_start inside of a file containing your functions, it gets started all of the time.
// creates a choice architecturally on how to develop applications


// Function to interact with the web service
function callWebService($action, $data = []) {
    global $api_endpoint, $api_key, $api_hash;

    $post_data = json_encode([
        'apikey' => APIKEY,
        'apihash' => APIHASH,
        'action' => $action,
        'data' => $data,
    ]);

    $ch = curl_init($api_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = 'cURL Error: ' . curl_error($ch);
        curl_close($ch);
        return ['result' => 'Error', 'data' => ['message' => $error]];
    }
    curl_close($ch);
    $result = json_decode($response, true);

    if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
        return ['result' => 'Error', 'data' => ['message' => 'JSON Decode Error: ' . json_last_error_msg(), 'raw_response' => $response]];
    }

    return $result;
}

// Function to fetch and display bookmarks
function displayBookmarks() {
    //global $user_id;
    $response = callWebService('getbookmarks', ['user_id' => $_SESSION['userDetails']['userid']]);

    $output = '';

    if ($response['result'] === 'Success' && is_array($response['data'])) {
        $output .= '<ul>';
        foreach ($response['data'] as $bookmark) {
			$output .= '<li>';
			$output .= '<a href="' . 'visit-bookmark.php?id=' . htmlspecialchars($bookmark['url']) . '" target="_blank">' . htmlspecialchars($bookmark['displayname']) . '</a> (Visits: ' . htmlspecialchars($bookmark['visits']) . ')';
		
			// Delete button as a link instead of a form
			$output .= '
				<a href="?action=deletebookmark&bookmark_id=' . urlencode($bookmark['bookmark_id']) . '" onclick="return confirm(\'Are you sure you want to delete this bookmark?\');">
					<button type="button">Delete</button>
				</a>
			';
		
			$output .= '</li>';
		}
        $output .= '</ul>';
    } else {
        $output .= '<p>No bookmarks found for this user.</p>';
        if ($response['result'] === 'Fail' || $response['result'] === 'Error') {
            $output .= '<p>Error fetching bookmarks: ' . htmlspecialchars(json_encode($response['data'])) . '</p>';
        }
    }
    echo $output;
}