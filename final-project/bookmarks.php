<?php

/*
bookmarks.php

page that shows logged in user their saved bookmarks

final functionality will include ability to add/delete/edit bookmarks
bookmarks on page are sorted based on click count (popularity with user)

User comes in from login page and will pass in session variable to determine valid login status
*/

namespace finalBookmarkProject; 


//Session was not needed due to being active inside of functions //session_start();

//keeping require_once here in code structure despite calling this before isset/empty
//BECAUSE we moved session_start() to functions.php, would rather keep all require_once calls together
//for increased readability
//using Page and our functions class
require_once("class/Page.php");
require_once("actions/functions.php");
require_once("../../creds.php");

//Stopping the User if they are not logged in.
if ($_SESSION['loggedIn'] == false || !isset($_SESSION['loggedIn']) ) { 
	$_SESSION['errors']['generic'] = "Please Log In.";
	die(header("Location: login.php"));
}

if (!isset($_POST['action'])) {
    $_POST['action'] = "";
}

//using Page class to create $bookmarkspage
$bookmarkspage = new Page("Bookmarks");
$bookmarkspage->addHeadElement("<link rel=\"stylesheet\" href=\"styles.css\">");

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

        } else {
            $_SESSION['message'] = 'Failed to add bookmark. Details: ' . json_encode($add_response['data']);

        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Delete bookmark via GET link
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'deletebookmark') {
    $bookmark_id = $_GET['bookmark_id'];

    if (!empty($bookmark_id)) {
        $delete_data = [
            'bookmark_id' => $bookmark_id,
            'user_id' => $_SESSION['userDetails']['userid'],
        ];
        $delete_response = callWebService('deletebookmark', $delete_data);

        if ($delete_response['result'] === 'Success') {
            $_SESSION['message'] = 'Bookmark deleted successfully!';
        } else {
            $_SESSION['message'] = 'Failed to delete bookmark. Details: ' . json_encode($delete_response['data']);
        }
    }
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}



//print beginning html and bookmarks header
print $bookmarkspage->getTopSection();
//start bookmark-wrapper div class
echo '<div class="bookmark-wrapper">';
echo '<h1 class="bookmarks-title">Bookmarks</h1>';

//Eric's code for saying hi to user
print "<div>Hello " . $_SESSION['userDetails']['name'] . "! </div>";
print $_SESSION['userDetails']['email'];

//CODE BODY GOES HERE - BOOKMARKS FORM + BUTTONS :)
echo '<div id="bookmarks-container">';
    displayBookmarks();
echo '</div>' . '<br>';



// Bookmark form displaying -- Might add functionality where "Delete" just deletes selected bookmarks in list.
echo '<form id="add-bookmark-form" method="post" action="">';
echo '<h2 class="add-bookmark-title">Add New Bookmark</h2>';

echo '<label for="url">URL:</label><br>';
echo '<input type="text" id="url" name="url" required><br><br>';

echo '<label for="displayname">Display Name:</label><br>';
echo '<input type="text" id="displayname" name="displayname" required><br><br>';

echo '<input type="hidden" name="action" value="addbookmark">';
echo '<button type="submit">Add Bookmark</button>';
echo '</form>' . '<br>';



echo '<div id="response-message">';
    // Display any messages from the add bookmark action
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']); // Clear the message after displaying
    }
echo '</div>';

//end bookmark-wrapper div class
echo '</div>';

//button to trigger logout.php
print "<br><br><br>" . "<form class=\"logout-form\" method='post' action=\"logout.php\">";
print "<button type='submit' name='logout'> Logout </button>";
print"</form>";

//print ending html
print $bookmarkspage->getBottomSection();