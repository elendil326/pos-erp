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

/**
* Archivo para el logging del sistema.
*
* @see logger.php
*/
require_once('../server/mx.caffeina.logger/logger.php');

/**
* Usuario de la base de datos.
*/
define('DB_USER', 'pos');

/**
* Contraseña de la base de datos.
*/
define('DB_PASSWORD', 'pos');

/**
* Nombre de la base de datos.
*/
define('DB_NAME', 'pos');

/**
* Nombre del driver para conectar al DBMS.
*/
define('DB_DRIVER', 'mysql');

/**
* Donde se encuentra el DBMS.
*
* IP | domain | localhost
*/
define('DB_HOST', 'localhost');


/**
* Habilitar o no el debug de ADOdb.
*
* true habilita debug. false deshabilita debug. 
*
* Default false.
*/
define('DB_DEBUG', false);

$conn = null;

try{
    $conn = ADONewConnection(DB_DRIVER);
    $conn->debug = DB_DEBUG;
    $conn->PConnect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if(!$conn) {
        throw new Exception("Error en la conexión a la base de datos.");
    }
} catch (exception $e) {
    global $logger;
    $logger->log($e->getMessage(), PEAR_LOG_EMERG);
}
?>
