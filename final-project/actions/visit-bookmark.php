<?php
namespace finalBookmarkProject;

/*
this file processes a user clicking on their saved bookmark
and then uses the web service to increment the visit count by one each time
before this, it checks if the user is logged in, 
the bookmark id is set, and that the id is a numeric value
*/

session_start();

require_once("functions.php");
require_once("../../creds.php");

// Ensure the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false) {
    $_SESSION['errors']['generic'] = "Please Log In.";
    die(header("Location: ../login.php"));
}

//user id and bookmark id information
$user_id_to_get => $_SESSION['userDetails']['userid'];
$bookmark_id_to_get = $_GET['id']; 


//isset/empty for $_GET['id'] from bookmark coming in 
if (!isset($_GET['id']) || $_GET['id'] == false) {
    $_SESSION['errors']['generic'] = "Bookmark is not set.";
    die(header("Location: ../bookmarks.php"));
}

//check isnumeric for id coming in
//if this statement passes, the id is valid and we're moving on
if(is_numeric($_GET['id'])){
    //call getbookmark() from web service (SINGULAR! DIFFERENT THAN getBookmarkS()!!)
    // Call addvisit() to increment the visit count
    $addvisit_data = [
        'bookmark_id' => $bookmark_id_to_get,
        'user_id' => $user_id_to_get,
    ];
    //holds response from web service call
    $addvisit_response = callWebService('addvisit', $addvisit_data);

    //if this call was succesful, then move on
    if ($addvisit_response['result'] === 'Success') {
        // Now, retrieve the bookmark details to get the URL for redirection
        $getbookmark_data = [
            'user_id' => $user_id_to_get,
            'bookmark_id' => $bookmark_id_to_get,
        ];
        $getbookmark_response = callWebService('getbookmark', $getbookmark_data);

        if ($getbookmark_response['result'] === 'Success' && isset($getbookmark_response['data']['url'])) {
            // Redirect to the actual bookmark URL
            die(header("Location: " . $getbookmark_response['data']['url']));
        } else {
            // Handle the case where retrieving the bookmark URL failed
            $_SESSION['errors']['generic'] = "Failed to retrieve bookmark URL.";
            header("Location: ../bookmarks.php");
            exit();
        }
    } else {
        // Handle the case where adding the visit failed
        $_SESSION['errors']['generic'] = "Failed to record visit.";
        header("Location: ../bookmarks.php");
        exit();
    }

}
else{
    //else, the id wasn't valid and we cannot continue. display error to user on bookmarks page
    $_SESSION['errors']['generic'] = "Bookmark is not valid.";
    die(header("Location: ../bookmarks.php"));
}

?>