<?php
session_start();
require_once("../../creds.php");
require_once("functions.php");

$_SESSION['errors'] = array();

$required = array("username" => "Please fill in the username",
                  "password" => "Please fill in the password",
                 );
foreach ($required as $field => $err) {
  if (!isset($_POST[$field]) || empty($_POST[$field])) {
    $_SESSION['errors'][$field] = $err;
  }
}

/*
when developing code, handle incorrect paths first ^^^

then handle correct/incorrect code second, in that order -->
*/

if (authUser($_POST['username'], $_POST['password'], PASSWORD_HASH) === true) {  //triple = checks for both the value and the type of variable (bool,integer,etc)
	$_SESSION['loggedIn'] = true;
	die(header("Location: landing.php"));
} else {
	$_SESSION['errors']['generic'] = "Username or password incorrect<br>";
}

if (count($_SESSION['errors']) > 0) {
	die(header("Location: final-login-1.php"));
}


