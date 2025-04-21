<?php

/*
authenticate-action.php

authenticates POST information sent from login.php

*/

session_start();
//comment
//hii

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

//debugging output (remove later)
//var_dump($client);
//var_dump($result);

//full Decode
$jsonResult = json_decode($result);  



//Checking for JSON Errors
if (json_last_error() !== JSON_ERROR_NONE) {

  //may want to redirect if JSON is wrong
  print "Result is not JSON";
  var_dump($jsonResult);
  exit;
}

$_SESSION['userDetails'] = array();
$_SESSION['userDetails']['name'] = $jsonResult->data->name;
$_SESSION['userDetails']['email'] = $jsonResult->data->email;


//Checking JSON Object Variables
if ($jsonResult->result == "Success") {
  //print("<div id=" . "result" . ">It Printed Success</div>");
  die(header("Location: bookmarks.php"));
} else {
  //print("<div id=" . "result" . ">It Printed Denied</div>");
  $_SESSION['errors']['generic'] = "Login was Unsuccessful.";
}



//End of Isset and Empty error checks, sends user back to Login
if (count($_SESSION['errors']) > 0) {
	die(header("Location: login.php"));
}



//Debugging Code -- Unviewable if User continues to Bookmarks Page.
print("<br><br>");
var_dump($jsonResult);  //can use web service stuff here
print("<br>" . $_POST['username'] . "  " . $_POST['password']);
