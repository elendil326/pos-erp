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


$core_conn = null;

try{

    $core_conn = ADONewConnection(POS_CORE_DB_DRIVER);
    $core_conn->debug = POS_CORE_DB_DEBUG;
    $core_conn->PConnect(POS_CORE_DB_HOST, POS_CORE_DB_USER, POS_CORE_DB_PASSWORD, POS_CORE_DB_NAME);

    if(!$core_conn) {

	    die( '{ "success" : false, "reason" : "NO_DB" }' );	
    }

} catch (Exception $e) {
	Logger::log($e);
	die( '{ "success" : false, "reason" : "NO_DB" }' );	

}
