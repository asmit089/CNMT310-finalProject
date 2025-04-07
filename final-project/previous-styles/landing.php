<?php
session_start();
require_once("functions.php");


if ($_SESSION['loggedIn'] == false || !isset($_SESSION['loggedIn']) ) { 
	$_SESSION['errors']['generic'] = "Please Log In.";
	die(header("Location: final-login-1.php"));
}


print getTop("Landing");

print "<br>";
print "Authentication Successful";

print "</body>";
print "</html>";



