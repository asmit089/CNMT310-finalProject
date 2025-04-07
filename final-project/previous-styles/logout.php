<?php

if (isset($_SESSION['loggedIn'])) {
	$_SESSION['loggedIn'] = false;  //setting to false & unsetting loggedIn first is necessary for people who close browsers quickly
	unset($_SESSION['loggedIn']);
}
if (is_array($_SESSION)) {
	foreach ($_SESSION as $key => $val) { //taking this out of the 'isset' line makes sure that all keys are removed
		$_SESSION[$key] = "";
		unset($_SESSION[$key]);
	}
}
$_SESSION = array();
session_write_close();
die(header("Location: final-login-1.php"));
