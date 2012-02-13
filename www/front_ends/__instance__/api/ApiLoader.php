<?php

require_once("../../../../server/bootstrap.php");

require_once("api/ApiLoader.php" );

$api_classname = "Api" . str_replace(" ", "", ucwords( str_replace("/", " ", $_GET["_api_"] ) ) ) ;

if(class_exists ( $api_classname ) === false) {
	Logger::error("Api `$api_classname` does not exist.");
	die(header("HTTP/1.1 404 NOT FOUND"));
}

$api = new $api_classname;	

$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
