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

//connect to the web service
$api_endpoint = 'https://cnmt310.classconvo.com/bookmarks/';

//using Page class to create $bookmarkspage
$bookmarkspage = new Page("Bookmarks");

//ADD ERROR CHECKING/AUTHENTICATION HERE LATER

//print beginning html and bookmarks header
print $bookmarkspage->getTopSection();


print "<div>Hello " . $_SESSION['userDetails']['name'] . "! </div>";
print $_SESSION['userDetails']['email'];

print "<h3>Bookmarks</h3>" . PHP_EOL;

//grab user details from the login page via SESSION and display their name
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

//print ending html
print $bookmarkspage->getBottomSection();
