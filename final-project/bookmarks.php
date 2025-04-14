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

//using Page class to create $bookmarkspage
$bookmarkspage = new Page("Bookmarks");

//ADD ERROR CHECKING/AUTHENTICATION HERE LATER

//print beginning html and bookmarks header
print $bookmarkspage->getTopSection();
print "<h1>Bookmarks</h1>" . PHP_EOL;

//CODE BODY GOES HERE - BOOKMARKS FORM + BUTTONS :)

//print ending html
print $bookmarkspage->getBottomSection();
