<?php
/**
* Este archivo es usado a traves de todo el sistema para proveer acceso a la
* base de datos.
*
* @author Manuel Alejandro Gómez Nicasio <alejandro.gomez@alejandrogomez.org>
* @package pos
* @subpackage db
* @uses logger.php
*/

/**
* Capa de abstracción para la base de datos. 
*
* @link http://adodb.sourceforge.net/ ADOdb
*/
require_once('adodb5/adodb.inc.php');
/**
* @ignore
*/
require_once('adodb5/adodb-exceptions.inc.php');

$conn = null;

try{
    $conn = ADONewConnection(DB_DRIVER);
    $conn->debug = DB_DEBUG;
    $conn->PConnect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if(!$conn) {
        throw new Exception("Error en la conexión a la base de datos.");
    }
} catch (exception $e) {
    //global $logger;
    //$logger->log($e->getMessage(), PEAR_LOG_EMERG);

	echo "{ \"success\" : false, \"reason\" : \"NO_DB\" }";
	exit;
}
?>
