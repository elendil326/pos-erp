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
* cargar configuracion
*
* @see config.php
*/
require_once('config.php');


/**
* Loggin del sistema.
*
* @todo agrear if(LOG)
* @see logger.php
*/
//require_once('mx.caffeina.logger/logger.php');

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



$args = $_REQUEST;
unset($_POST);
unset($_GET);



//main dispatching
switch( ((int)($args['action'] / 100))*100 )
{
	
	case 100: 
		require_once('controller/clientes.controller.php');
	break;
	
	case 200:
		require_once('controller/compras.controller.php');
	break;
	
	case 300:
		require_once('controller/ventas.controller.php');
	break;
	
	case 400: break;
	
	case 500: break;
	
	case 600: break;
	
}

return;

