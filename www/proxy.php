<?php
/**
* This is the proxy between the application and the server side.
*
* Es solo un enlace para salir del document root y llegar a los archivos donde estan
* implementado todo.
*
* @author Manuel Alejandro Gómez Nicasio <alejandro.gomez@alejandrogomez.org>
* @see dispatcher.php
* @package pos
* @uses dispatcher.php
*/

/*

	This is the proxy betwen the application and the server side, heavy securtity should 
	be applied here, to leave the businness logic on the server folder.

*/

define("I_AM_PROXY" ,true);

/**
* Archivo principal del sistema
*/
require_once("../server/dispatcher.php");
