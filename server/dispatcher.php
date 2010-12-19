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
* Conexión a la base de datos.
*
* @see DBConnection.php
*/
require_once('db/DBConnection.php');


/*
 * el action 'echo' es solo para probar conexion con la base de datos
 */
if( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'testDB'))
{
	die( '{ "success": true }') ;

}







//Comprobamos que la variable que trae la funcion a ejecutar exista y despues 
//entramos al switch.
if ( !isset($_REQUEST['action']) )
{
	echo "{ \"success\": false , \"reason\" : \"Invalid method call for dispatching.\" }";
        return;
}




/**
* Validar el user agent
*
*/
//verificar sesion, a menos que sea un action 2001, que solo verifica conectividad y conexion con la base de datos,
//la accion 2001 tambien envia como parametro desde que ip viene este request para y lo compara en la base de datos
//para ver si es el ip que esta guardado en la base de datos
if( ! ($_REQUEST['action']  == "2001" || $_REQUEST['action']  == "2004") )
{
    /*
	if ( !isset($_SESSION['HTTP_USER_AGENT']) )
	{
		//si no tiene ni el valor de sesion en http_user_agent a la verga
		echo "{\"succes\": false , \"reason\": \"Sesion invalida\", \"text\" : \"Sesion invalida\" }";
		exit;
	}
	
	//verificar que no se haya modificado el user agent, el user agent esta encriptado para que no pueda 
	//ver cual es, asi como los demas datos
	if ( ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) )
	{
		//log security breach
		echo "{\"succes\": false , \"reason\": \"Sesion invalida\", \"text\" : \"Sesion invalida\" }";
		exit;
	}	*/
    
}





/*
foreach( $_REQUEST as $r ){
	$args = stripslashes( $r ) ....
}
*/
$args = $_REQUEST;
unset($_POST);
unset($_GET);


//main dispatching
switch( ((int)($args['action'] / 100))*100 )
{
	
	case 100: 
		require_once('controller/mostrador.controller.php');
	break;
	
	case 200:
		require_once('controller/autorizaciones.controller.php');
	break;
	
	case 300:
		require_once('controller/clientes.controller.php');
	break;
	
	case 400: 
		require_once('controller/inventario.controller.php');
	break;
	
	case 500: 
		require_once('controller/personal.controller.php');
	break;
	
	case 600: 
		require_once('controller/efectivo.controller.php');
	break;

	case 700:
		require_once('controller/sucursales.controller.php');
	break;

	case 800:
		require_once('controller/ventas.controller.php');
	break;

	case 900:
	
	break;
	
	case 2000:
		/* alan : login 
		 * funciones de inicio de sesion
		 */
		require_once('controller/login.controller.php');
	break;
	
}

return;

