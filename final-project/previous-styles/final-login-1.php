<?php
session_start();
require_once("functions.php");
$_SESSION['loggedIn'] = false;


print getTop("Login");   //functions.php -- can assign function to a variable, or just print it.


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

print $generic_err;
print "<form action=\"final-login-process-1.php\" method=\"POST\">";
print "Username: <input type=\"text\" name=\"username\"> " . $username_err . "<br>"; //error messages added to form elements to appear beside the input box.
print "Password: <input type=\"password\" name=\"password\"> " . $password_err . "<br>";
print "<input type=\"submit\" name=\"submit\">";
print "</form>";

print "</body>";
print "</html>";
