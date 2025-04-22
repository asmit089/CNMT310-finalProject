<?php

/*
login.php

displays the login form to the user for CNMT310 final project

It uses Page.php for the opening/closing html
*/

namespace finalBookmarkProject;     
//i am adding a comment

session_start();

//using Page class
require_once("class/Page.php");
require_once("actions/functions.php");
$_SESSION['loggedIn'] = false;

//print getTop("Login");   //functions.php -- can assign function to a variable, or just print it.
//using Page class to create $loginpage
$loginpage = new Page("Login");
//css link
$loginpage->addHeadElement("<link rel=\"stylesheet\" href=\"styles.css\">");
$loginpage->prepareTopSection();

$inputs = array("username","password","generic"); //this array holds names for specific error messages.
foreach ($inputs as $inputname) {
  ${$inputname . "_err"} = ""; //creating a variable with a dynamic name.
}
if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
  foreach ($_SESSION['errors'] as $field => $error) {
    ${$field . "_err"} = $error;
  }
  $_SESSION['errors'] = array();
}

//print beginning html and login page title
print $loginpage->getTopSection();
print "<h1>Login Page</h1>" . PHP_EOL;

print $generic_err;
print("<div id=\"username\">" . $username_err . "</div>");
print("<div id=\"password\">" . $password_err . "</div>");
print "<form action=\"authenticate-action.php\" method=\"POST\">";
print "Username: <input type=\"text\" name=\"username\"> " . "<br>"; //error messages added to form elements to appear beside the input box.
print "Password: <input type=\"password\" name=\"password\"> " . "<br>";
print "<input type=\"submit\" name=\"submit\">";
print "</form>";

//print ending html
print $loginpage->getBottomSection();
