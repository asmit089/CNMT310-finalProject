<?php

/*
authenticate-action.php

authenticates POST information sent from login.php

*/

session_start();

require_once("class/WebServiceClient.php");
require_once("../../creds.php");

$url = "https://cnmt310.classconvo.com/bookmarks/";
$client = new WebServiceClient($url);


//Checking both Username and Password being set
$_SESSION['errors'] = array();
$required = array("username" => "Please fill in the username",
                  "password" => "Please fill in the password",
                 );
foreach ($required as $field => $err) {
  if (!isset($_POST[$field]) || empty($_POST[$field])) {
    $_SESSION['errors'][$field] = $err;
  }
}


//Submitting Username and Password to JSON WebService
$data = array("username" => $_POST['username'], 
				"password" => $_POST['password']);//$_POST['password']);
				
$action = "authenticate";  //will change per web service (add bookmark, etc)

$fields = array("apikey" => APIKEY,
                "apihash" => APIHASH,
			          "action" => $action,
                "data" => $data
             );
$client->setPostFields($fields); 
$result = $client->send();

//full Decode
$jsonResult = json_decode($result);  



//Checking for JSON Errors
if (json_last_error() !== JSON_ERROR_NONE || !isset($jsonResult->result)) {
  
  $_SESSION['errors']['generic'] = "Authentication Error.";
  die(header("Location: login.php"));

}


//Checking JSON Object Variables - Variables stored in here for security.
if ($jsonResult->result === "Success" && isset($jsonResult->data)) {
  
  $_SESSION['loggedIn'] = true;
  $_SESSION['userDetails'] = [
    "name" => $jsonResult->data->name,
    "email" => $jsonResult->data->email,
    "userid" => $jsonResult->data->id,
  ];
  $_SESSION['apikey'] = APIKEY;
  $_SESSION['apihash'] = APIHASH;
  die(header("Location: bookmarks.php"));

} else {
  $_SESSION['errors']['generic'] = "Login was Unsuccessful.";

}

//End of Isset and Empty error checks, sends user back to Login
if (count($_SESSION['errors']) > 0) {
	die(header("Location: login.php"));
}
