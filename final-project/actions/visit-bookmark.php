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


    //redirect to actual bookmark url here
    //use die header like in previous labs
    //die(header(user bookmark url here));
}
else{
    //else, the id wasn't valid and we cannot continue. display error to user on bookmarks page
    $_SESSION['errors']['generic'] = "Bookmark is not valid.";
    die(header("Location: ../bookmarks.php"));
}

?>