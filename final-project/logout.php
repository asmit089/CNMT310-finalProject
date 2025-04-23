<?php
	//user clicked log out button
    if (isset($_POST['logout'])) {
		//if loggedIn, make not loggedIn
		if (isset($_SESSION['loggedIn'])) {
			$_SESSION['loggedIn'] = false;  //setting to false & unsetting loggedIn first is necessary for people who close browsers quickly
			unset($_SESSION['loggedIn']);
		}
		//remove all user information
		if (is_array($_SESSION)) {
			foreach ($_SESSION as $key => $val) { //taking this out of the 'isset' line makes sure that all keys are removed
				$_SESSION[$key] = "";
				unset($_SESSION[$key]);
			}
		}
		
		$_SESSION = array();
		session_write_close();
		//push user back to login page
		die(header("Location: login.php"));
	}
