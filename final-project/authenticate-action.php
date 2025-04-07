<?php

session_start();

//check isset/empty for username & password $_POST

require_once("class/WebServiceClient.php");
require_once("../../creds.php");

$url = "https://cnmt310.classconvo.com/bookmarks/";
$client = new WebServiceClient($url);

//$_POST['username'],  //will change per web service(add bookmark,etc)
$data = array("username" => "lowryp", 
				"password" => "h9zHEcBww");//$_POST['password']);
				
$action = "authenticate";  //will change per web service (add bookmark, etc)

$fields = array("apikey" => APIKEY,
             "apihash" => APIHASH,
			 "action" => $action,
              "data" => $data
             );
$client->setPostFields($fields);
$result = $client->send();



$jsonResult = json_decode($result);  //tests JSON between services

var_dump($result);

if (json_last_error() !== JSON_ERROR_NONE) {

  //may want to redirect if JSON is wrong
  print "Result is not JSON";
  var_dump($jsonResult);
  exit;
}

var_dump($jsonResult);  //can use web service stuff here
