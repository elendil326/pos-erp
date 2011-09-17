<?php
/**
* Este archivo es usado a traves de todo el sistema para proveer acceso a la
* base de datos.
*
* @author Alan Gonzalez
* @package pos
* @subpackage db
* @uses logger.php
*/



$conn = null;

try{

    $conn = ADONewConnection(DB_DRIVER);
    $conn->debug = DB_DEBUG;
    $conn->PConnect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if(!$conn) {

	    die( '{ "success" : false, "reason" : "NO_DB" }' );	
    }

} catch (Exception $e) {

	die( '{ "success" : false, "reason" : "NO_DB" }' );	

}
