<?php
namespace finalBookmarkProject;

session_start();

require_once("functions.php");
require_once("../../creds.php");

// Ensure the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false) {
    $_SESSION['errors']['generic'] = "Please Log In.";
    die(header("Location: ../login.php"));
}

//if bookmark is set, then grab information
if (isset($_GET['bookmark_id'])) {
    $bookmark_id = $_GET['bookmark_id'];
    $user_id = $_SESSION['userDetails']['userid'];

    if (!empty($bookmark_id) && !empty($user_id)) {
        $addvisit_data = [
            'bookmark_id' => $bookmark_id,
            'user_id' => $user_id,
        ];
        $addvisit_response = callWebService('addvisit', $addvisit_data);

        if ($addvisit_response['result'] === 'Success') {
            // Assuming your displayBookmarks function fetches the 'url'
            // along with other bookmark details. You'll need to make
            // sure that URL is accessible here.
            // For now, let's assume you can fetch the URL based on $bookmark_id.
            $bookmark_url = getBookmarkURL($bookmark_id); // You'll need to implement this function

            if ($bookmark_url) {
                header("Location: " . $bookmark_url);
                exit();
            } else {
                $_SESSION['message'] = 'Error: Could not retrieve bookmark URL.';
                header("Location: bookmarks.php"); // Redirect back to bookmarks page
                exit();
            }
        } else {
            $_SESSION['message'] = 'Failed to record visit. Details: ' . json_encode($addvisit_response['data']);
            header("Location: bookmarks.php"); // Redirect back to bookmarks page
            exit();
        }
    } else {
        $_SESSION['message'] = 'Invalid bookmark ID.';
        header("Location: bookmarks.php"); // Redirect back to bookmarks page
        exit();
    }
} else {
    $_SESSION['message'] = 'No bookmark ID provided.';
    header("Location: bookmarks.php"); // Redirect back to bookmarks page
    exit();
}

// You'll need to implement a function to fetch the bookmark URL
// based on the bookmark_id from your data source (likely a database)
function getBookmarkURL($bookmark_id) {
    // Replace this with your actual database query to fetch the URL
    // based on the $bookmark_id.
    // Example (assuming you have a database connection established elsewhere):
    global $pdo; // Assuming you have a PDO connection

    $stmt = $pdo->prepare("SELECT url FROM bookmarks WHERE bookmark_id = :bookmark_id AND user_id = :user_id");
    $stmt->bindParam(':bookmark_id', $bookmark_id);
    $stmt->bindParam(':user_id', $_SESSION['userDetails']['userid']);
    $stmt->execute();
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($result && isset($result['url'])) {
        return $result['url'];
    }
    return false;
}

?>