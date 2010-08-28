<?php
/**
* Archivo principal del sistema, por aquí pasan todas la peticiones del cliente.
*
* Este archivo incluye los scripts que estan disponibles en todo el sistema. Ademas
* gestiona los niveles de seguridad de los usuarios y recibe los datos necesarios
* para despues pasarlos a la aplicación adecuada.
*
* @package pos
*/

/*
 * Set time zone
 * */
date_default_timezone_set('America/Mexico_City');

/**
 * iniciar la sesion y comprobar seguridad 
 */
$ss = session_start (  );

if(!$ss){
	echo "{\"success\": false , \"reason\": -1,  \"text\" : \"Imposible iniciar sesion. Debe habilitar las cookies para ingresar.\" }";
	return;
}



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
require_once('mx.caffeina.logger/logger.php');


/**
* Conexión a la base de datos.
*
* @see DBConnection.php
*/
require_once('db/DBConnection.php');


/*
 * el action 'echo' es solo para probar conexion con la base de datos
 */
if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'test'))
{
	echo "{ \"success\": true , \"payload\" : 200 }";
	exit;
}


/**
* Validar el user agent
*
*/
if( isset($_REQUEST['action'])  && ($_REQUEST['action']  != "2001") )
{
	
	if ( !isset($_SESSION['HTTP_USER_AGENT']) )
	{
		//si no tiene ni el valor de sesion en http_user_agent a la verga
		echo "{\"succes\": false , \"reason\": 31416, \"text\" : \"Please re-log in.\" }";
		exit;
	}
	
	//verificar que no se haya modificado el user agent, el user agent esta encriptado para que no pueda 
	//ver cual es, asi como los demas datos
	if ( ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) )
	{
		//log security breach
		echo "{\"succes\": false , \"reason\": 31416, \"text\" : \"Please re-log in.\" }";
		exit;
	}	
}





//Comprobamos que la variable que trae la funcion a ejecutar exista y despues 
//entramos al switch.
if ( !isset($_REQUEST['action']) )
{
	echo "{ \"success\": false , \"reason\" : \"Invalid method call for dispatching.\" }";
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
	
	case 400: 
		require_once('controller/view_ventas.controller.php');
	break;
	
	case 500: 
		require_once('controller/view_detalle_venta.controller.php');
	break;
	
	case 600: 
		require_once('controller/view_compras.controller.php');
	break;

	case 700:
		require_once('controller/view_detalle_compra.controller.php');
	break;

	case 800:
		require_once('controller/view_gastos.controller.php');
		require_once('controller/view_ingresos.controller.php');
	break;

	case 900:
		/* alan : test 
	 	* funciones para probar el dao
	 	*/
		require_once('controller/test.controller.php');
	break;

	case 1000:
		require_once('controller/clientes.controller.php');
	break;
	
	case 1100: 
		require_once('controller/proveedores.controller.php');
	break;
	
	case 1200:
		require_once('controller/compras.controller.php');
	break;
	
	case 1300:
		require_once('controller/pagos_compras.controller.php');
	break;
	
	case 1400:
		require_once('controller/clientes_ventas.controller.php');
	break;
	
	case 1500:
		require_once('controller/cortes.controller.php');
	break;
	
	case 1600:
		require_once('controller/efectivo.controller.php');
	break;
	
	case 1700:
		require_once('controller/inventario.controller.php');
	break;
	
	case 1800:
		require_once('controller/facturas_ventas.controller.php');
	break;
	
	case 2000:
		/* alan : login 
		 * funciones de inicio de sesion
		 */
		require_once('controller/login.controller.php');
	break;
	
	case 2100:
		/* alan : mostrador 
		 * funciones de venta de mostrador
		 */
		require_once('controller/mostrador.controller.php');
	break;
	
	case 2300:
		/* manejar usuarios en admin*/
		require_once('controller/usuarios.controller.php');
	break;
	
	case 2200:
		/* sucursal stuff*/
		require_once('controller/sucursal.controller.php');
	break;
	
	case 2400:
		require_once('controller/pagos_ventas.controller.php');
	break;
}

return;

