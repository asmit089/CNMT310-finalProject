<?php
namespace finalBookmarkProject;

/*
this file processes a user clicking on their saved bookmark
and then uses the web service to increment the visit count by one each time
before this, it checks if the user is logged in, 
the bookmark id is set, and that the id is a numeric value
*/

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true) {
    $_SESSION['errors']['generic'] = "Please Log In.";
    die(header("Location: ../login.php"));
}

//isset/empty for $_GET['id'] from bookmark coming in 
if (!isset($_GET['id']) || !isset($_GET['burl'])) {
    $_SESSION['message'] = "Bookmark is not set.";
    die(header("Location: ../bookmarks.php"));
}

//check is set and empty before we bring in more code 
require_once("functions.php");
require_once("../../../creds.php");


$addvisit_data = [
    'bookmark_id' => $_GET['id'],
    'user_id' => $_SESSION['userDetails']['userid'],
];

$addvisit_response = callWebService('addvisit', $addvisit_data);

//if this call was succesful, then move on
if ($addvisit_response['result'] === 'Success') {
    die(header("Location: " . $_GET['burl']));
        
} else {
    $_SESSION['message'] = "Failed to record visit.";
    die(header("Location: ../bookmarks.php"));
}


?>