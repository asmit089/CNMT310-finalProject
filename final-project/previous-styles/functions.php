<?php

// running session_start inside of a file containing your functions, it gets started all of the time.
// creates a choice architecturally on how to develop applications

function getTop($title) {
	$html = "";
	$html .= "<!doctype html>";
	$html .= "<html lang=\"en\">";
	$html .= "<head>\n";
	$html .= "<title>" . $title . "</title>";
	$html .= "</head>";
	$html .= "<body>";
	
	return $html; //how to properly end/use a function
}

function authUser($user,$pass,$passHash) {
	
	$result = false;
	if ($user == "admin" && password_verify($pass,$passHash)) {
		$result = true;
	}
	
	return $result;
	
}