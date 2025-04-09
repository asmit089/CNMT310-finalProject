<?php

/*
login.php

displays the login form to the user for CNMT310 final project

It uses Page.php for the opening/closing html
*/

namespace finalBookmarkProject;

session_start();

//using Page class
require_once("Page.php");
require_once("actions/functions.php");
$_SESSION['loggedIn'] = false;

//print getTop("Login");   //functions.php -- can assign function to a variable, or just print it.
//using Page class to create $loginpage
$loginpage = new Page("Login");

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
print "<form action=\"authenticate-action.php\" method=\"POST\">";
print "Username: <input type=\"text\" name=\"username\"> " . $username_err . "<br>"; //error messages added to form elements to appear beside the input box.
print "Password: <input type=\"password\" name=\"password\"> " . $password_err . "<br>";
print "<input type=\"submit\" name=\"submit\">";
print "</form>";

//print ending html
print $loginpage->getBottomSection();
