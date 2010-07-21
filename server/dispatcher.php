<?php
/**
* Archivo principal del sistema, por aquí pasan todas la peticiones del cliente.
*
* Este archivo incluye los scripts que estan disponibles en todo el sistema. Ademas
* gestiona los niveles de seguridad de los usuarios y recibe los datos necesarios
* para despues pasarlos a la aplicación adecuada.
*
* @author Manuel Alejandro Gómez Nicasio <alejandro.gomez@alejandrogomez.org>
* @package pos
*/

/**
* Loggin del sistema.
*
* @see logger.php
*/
require_once('mx.caffeina.logger/logger.php');

/**
* Conexión a la base de datos.
*
* @see DBConnection.php
*/
require_once('db/DBConnection.php');

//Comprobamos que la variable que trae la funcion a ejecutar exista y despues 
//entramos al switch.
if ( !isset($_REQUEST['action']) )
{
	echo "{ \"success\": false }";
        return;
}

switch ($_REQUEST['action']) {
    case 'insert_customer':
        $rfc = $_REQUEST['rfc'];
        $nombre = $_REQUEST['nombre'];
        $direccion = $_REQUEST['direccion'];
        $limite_credito = $_REQUEST['limite_credito'];
        $descuento = $_REQUEST['descuento'];
        $telefono = $_REQUEST['telefono'];
        $e_mail = $_REQUEST['e_mail'];
        unset($_REQUEST);
        $ans = insert_customer($rfc, $nombre, $direccion, $limite_credito, $descuento, $telefono, $e_mail);
        echo $ans;
        break;
}

//switch enorme para ejectuar un action de algun modelo.
//aquí va la seguridad del sistema (ACL)
