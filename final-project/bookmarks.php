<?php

/*
bookmarks.php

page that shows logged in user their saved bookmarks

final functionality will include ability to add/delete/edit bookmarks
bookmarks on page are sorted based on click count (popularity with user)

User comes in from login page and will pass in session variable to determine valid login status
*/

namespace finalBookmarkProject; 

session_start();

//using Page and our functions class
require_once("class/Page.php");
require_once("actions/functions.php");
require_once("../../creds.php");

//Stopping the User if they are not logged in.
if ($_SESSION['loggedIn'] == false || !isset($_SESSION['loggedIn']) ) { 
	$_SESSION['errors']['generic'] = "Please Log In.";
	die(header("Location: login.php"));
}


//connect to the web service
$api_endpoint = 'https://cnmt310.classconvo.com/bookmarks/';

//using Page class to create $bookmarkspage
$bookmarkspage = new Page("Bookmarks");


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
            $output .= '<li><a href="' . htmlspecialchars($bookmark['url']) . '">' . htmlspecialchars($bookmark['displayname']) . '</a> (Visits: ' . htmlspecialchars($bookmark['visits']) . ')</li>';
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

// Handle the "addbookmark" form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'addbookmark') {
    $url = $_POST['url'];
    $displayname = $_POST['displayname'];

    if (!empty($url) && !empty($displayname)) {
        $add_data = [
            'url' => $url,
            'displayname' => $displayname,
            'user_id' => $_SESSION['userDetails']['userid'],
        ];
        $add_response = callWebService('addbookmark', $add_data);

        if ($add_response['result'] === 'Success' && isset($add_response['data']['bookmark_id'])) {
            $_SESSION['message'] = 'Bookmark added successfully!';
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh and potentially see updated bookmarks
            exit();
        } else {
            $_SESSION['message'] = 'Failed to add bookmark. Details: ' . json_encode($add_response['data']);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['message'] = 'Please fill in both the URL and Display Name.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}



//print beginning html and bookmarks header
print $bookmarkspage->getTopSection();
print "<h1>Bookmarks</h1>" . PHP_EOL;

//Eric's code for saying hi to user
print "<div>Hello " . $_SESSION['userDetails']['name'] . "! </div>";
print $_SESSION['userDetails']['email'];

//CODE BODY GOES HERE - BOOKMARKS FORM + BUTTONS :)
echo '<div id="bookmarks-container">';
    displayBookmarks();
echo '</div>';

echo '<h2>Add New Bookmark</h2>';

echo '<form id="add-bookmark-form" method="post" action="">';
echo '<label for="url">URL:</label><br>';
echo '<input type="text" id="url" name="url" required><br><br>';

echo '<label for="displayname">Display Name:</label><br>';
echo '<input type="text" id="displayname" name="displayname" required><br><br>';

echo '<input type="hidden" name="action" value="addbookmark">';
echo '<button type="submit">Add Bookmark</button>';
echo '</form>';

echo '<div id="response-message">';
    // Display any messages from the add bookmark action
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
echo '</div>';

//button to trigger logout.php
//echo '<form id="add-bookmark-form" method="post" action="">';
print '<form id = "logout-button-form" method="post" action="logout.php">';
print "<button type='submit' name='logout'>Logout</button>";
print"</form>";

//print ending html
print $bookmarkspage->getBottomSection();