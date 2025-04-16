<?php

/*
authenticate-action.php

authenticates POST information sent from login.php

*/

session_start();
//comment
//check isset/empty for username & password $_POST
//hii

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

//debugging output (remove later)
//var_dump($client);
//var_dump($result);
$jsonResult = json_decode($result);  




if (json_last_error() !== JSON_ERROR_NONE) {

  //may want to redirect if JSON is wrong
  print "Result is not JSON";
  var_dump($jsonResult);
  exit;
}


if ($jsonResult->result == "Success") {
  print("<div>It Printed Success</div>");
} else {
  print("<div>It Printed Denied</div>");
}



print("<br><br>");
var_dump($jsonResult);  //can use web service stuff here
