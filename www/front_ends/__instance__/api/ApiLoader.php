<?php

require_once("../../../../server/bootstrap.php");

require_once("api/ApiLoader.php" );

$api_classname = "Api" . str_replace(" ", "", ucwords( str_replace("/", " ", $_GET["_api_"] ) ) ) ;

if(class_exists ( $api_classname ) === false) {
	die("NJOPE");
}

$api = new $api_classname;	

$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);