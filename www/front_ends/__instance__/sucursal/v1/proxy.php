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



require_once( "../../../../../server/bootstrap.php" );

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
define("WHO_AM_I" , "PROXY");

if( isset($_GET['action'])  && ($_GET['action'] == 666) ){
	var_dump($_SESSION);
	die();
}


//Comprobamos que la variable que trae la funcion a ejecutar exista y despues 
//entramos al switch.
if ( !isset($_REQUEST['action']) )
{
	echo '{ "success": false , "reason" : "Invalid method call for dispatching." }';	
    Logger::log("Invalid method call for dispatching. No hay action en el request.");
    return;
}


//require_once('controller/login.controller.php');


//validar los parametros de la conexion, salvo para estos dos que necesitan llegar
//a sus controllers, son verificar estado de sesion y hacer login, dado que al inicio
//no hay token, pues hay que saltar esta validacion, para todas las demas se debera pasar
if( ! (
	//revisar estado de sesion en sucursal
	$_REQUEST['action']  == "2001" 
	
	//logout
	|| $_REQUEST['action']  == "2002" 
	
	//Login de sucursal
	|| $_REQUEST['action']  == "2004" 
	
	//login de admin o ingeniero
	|| $_REQUEST['action']  == "2099"
	
	//login de clientes
	|| $_REQUEST['action']  == "2009"
	
	//client application handlers
	|| ($_REQUEST['action']  >= 1400 && $_REQUEST['action'] <= 1499)
) )
{
	/*
	if(!checkCurrentSession()){
		Logger::log("Sesion invalida ! Cerrando la sesion y forzando reboot.");
		
		//cerrar esta sesion
		logOut(false);
		
		//morir con un js que diga que hay que salir
		die( '{"success": false , "reason": "Accesso denegado" , "reboot" : true }' );
	}
	* */
    
}

//@todo filtrar ciertas cosas solo para el cliente


//@todo clean the request parameters
$args = $_REQUEST;

unset($_POST);
unset($_GET);


if( ! (($args['action'] == 1101) || ($args['action'] == 207) )){
	Logger::log("Request for action ".$args['action']." ");	
}


//main dispatching
//switch( ((int)($args['action'] / 100))*100 )
switch( ((int)($args['action'] ))  )
{
	
	case 100: 
		require_once('controller/mostrador.controller.php');
	break;
	
	case 200:
		require_once('controller/autorizaciones.controller.php');
	break;
	
	case 300:
		$res = array();
		$res["success"] = true;
		$res["hash"]= md5( json_encode($c = ClientesController::Buscar()) );
		$res["datos"] = $c["resultados"];
		
		for ($i=0; $i < sizeof($res["datos"]); $i++) { 
			$res["datos"][$i]["id_cliente"] = $res["datos"][$i]["id_usuario"];
			$res["datos"][$i]["razon_social"] = $res["datos"][$i]["nombre"];
			$res["datos"][$i] = array_merge( $res["datos"][$i], $res["datos"][$i]["direccion"] );
			unset( $res["datos"][$i]["direccion"] );
			$res["datos"][$i]["fecha_ingreso"] = $res["datos"][$i]["fecha_asignacion_rol"];
			
/*			
            "rfc": "MELA760306F50",
            "razon_social": "ALVARO MERINO LANUZA",
            "calle": "ANDEN E ",
            "numero_exterior": "BODEGA 33",
            "numero_interior": "",
            "colonia": "CENTRAL DE ABASTOS DE BAJIO B.JUAREZ A.C.",
            "referencia": "",
            "localidad": "",
            "municipio": "APASEO EL GRANDE",
            "estado": "GUANAJUATO",
            "pais": "MEXICO",
            "codigo_postal": "38192",
            "telefono": "52*17416*1",
            "e_mail": "",
            "limite_credito": "8000",
            "descuento": "0",
            "activo": "1",
            "id_usuario": "95",
            "id_sucursal": "5",
            "fecha_ingreso": "2011-01-03 22:09:14",
            "password": null,
            "last_login": null,
            "grant_changes": "0",
            "credito_restante": 6249.2799987793*/


			unset($res["datos"][$i]["password"] );
			unset($res["datos"][$i]["id_usuario"] );
	        unset($res["datos"][$i]["id_direccion"] );
	        unset($res["datos"][$i]["id_direccion_alterna"] );
	        unset($res["datos"][$i]["id_sucursal"] );
	        unset($res["datos"][$i]["id_rol"] );
	        unset($res["datos"][$i]["id_clasificacion_cliente"] );
	        unset($res["datos"][$i]["id_clasificacion_proveedor"] );
	        unset($res["datos"][$i]["id_moneda"] );
	        
	        unset($res["datos"][$i]["curp"] );
	        unset($res["datos"][$i]["comision_ventas"] );
	        unset($res["datos"][$i]["telefono_personal1"] );
	        unset($res["datos"][$i]["telefono_personal2"] );
	        unset($res["datos"][$i]["fecha_alta"] );
	        unset($res["datos"][$i]["fecha_baja"] );
	        unset($res["datos"][$i]["last_login"] );
	        unset($res["datos"][$i]["consignatario"] );
	        unset($res["datos"][$i]["salario"] );
	        unset($res["datos"][$i]["pagina_web"] );
	        unset($res["datos"][$i]["saldo_del_ejercicio"] );
	        unset($res["datos"][$i]["ventas_a_credito"] );
	        unset($res["datos"][$i]["representante_legal"] );
	        unset($res["datos"][$i]["facturar_a_terceros"] );
	        unset($res["datos"][$i]["dia_de_pago"] );
	        unset($res["datos"][$i]["mensajeria"] );
	        unset($res["datos"][$i]["intereses_moratorios"] );
	        unset($res["datos"][$i]["denominacion_comercial"] );
	        unset($res["datos"][$i]["dias_de_credito"] );
	        unset($res["datos"][$i]["cuenta_de_mensajeria"] );
	        unset($res["datos"][$i]["dia_de_revision"] );
	        unset($res["datos"][$i]["codigo_usuario"] );
	        unset($res["datos"][$i]["dias_de_embarque"] );
	        unset($res["datos"][$i]["tiempo_entrega"] );
	        unset($res["datos"][$i]["cuenta_bancaria"] );
	        unset($res["datos"][$i]["id_tarifa_compra"] );
	        unset($res["datos"][$i]["tarifa_compra_obtenida"] );
	        unset($res["datos"][$i]["id_tarifa_venta"] );
	        unset($res["datos"][$i]["tarifa_venta_obtenida"] );


		}
		
		echo json_encode($res);
	break;
	case 309:
	
		//lista de compras de un cliente
		/*
		
		{
		    "id_venta": "14137",
		    "id_venta_equipo": "4073",
		    "id_equipo": "5",
		    "id_cliente": "1",
		    "tipo_venta": "credito",
		    "tipo_pago": null,
		    "fecha": "2011-10-10 13:40:27",
		    "subtotal": "840",
		    "iva": null,
		    "descuento": "0",
		    "total": "840",
		    "id_sucursal": "5",
		    "id_usuario": "95",
		    "pagado": "840",
		    "cancelada": "0",
		    "ip": "189.163.64.93",
		    "liquidada": "1",
		    "detalle_venta": [
		        {
		            "id_venta": "14137",
		            "id_producto": "3",
		            "cantidad": "0",
		            "cantidad_procesada": "120",
		            "precio": "0",
		            "precio_procesada": "7",
		            "descuento": "0",
		            "descripcion": "PAPA TERCERA"
		        }
		    ],
		    "sucursal": "PAPAS SUPREMAS 3",
		    "cajero": "RAUL ALEJANDRO LEMUS FLORES",
		    "razon_social": "ALVARO MERINO LANUZA",
		    "factura": null
		}
		
		*/
		
		//$_GET["id_cliente"]
		print('{"success": true, "datos" : [ ] }');
		
	break;
	case 400: 
		print('{ "success": true, "hash" : "d751713988987e9331980363e24189ce" , "datos": [] }');
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
		require_once('controller/proveedor.controller.php');
	break;
	
	case 1000:
		require_once('controller/compras.controller.php');
	break;
	
	case 1100:
	
		$action = $args['action'];
		unset ( $args['action'] );
		require_once('controller/printer.controller.php');
		require_once('controller/sucursales.controller.php');		
		$args['action'] = $action;
		
		require_once('controller/pos.controller.php');
	break;
	case 1101:
		
	break;
	
	case 1104:
		print("{}");
	break;
	case 1106:
		$_config = array();
		$_config["POS_MULTI_SUCURSAL"] 		= true;//POS_MULTI_SUCURSAL;
		$_config["POS_COMPRA_A_CLIENTES"] 	= true;//POS_COMPRA_A_CLIENTES;
		$_config["POS_MODULO_CONTABILIDAD"] = false;//POS_MODULO_CONTABILIDAD;
		
		$_config["EXT_AJAX_TIMEOUT"] 		= 10000;
		$_config["CHECK_DB_TIMEOUT"] 		= 15000;

		$_config["POS_INFO_SUCURSAL"] 		= null;
		$_config["POS_DOCUMENTOS"] 			= null;
		$_config["POS_LEYENDAS_TICKET"] 	= null;
		
		echo ( json_encode( $_config ) );
	break;
	case 1200:
	    require_once('controller/factura.controller.php');
	break;

    case 1300:
	    require_once('controller/printer.controller.php');
	break;
	
	case 1400:
	    require_once('controller/client.controller.php');
	break;
	
	case 2000:
	
	break;
	case 2001:
		print(  '{"success":true, "sesion":true }' );
	break;
	
	case 2005:
		//dispatch
		$debug = isset($args['DEBUG']) ? "?debug" : "";
		echo "<script>window.location = 'sucursal.php" . $debug."'; </script>"; 
	break;

	break;
	
}

return;

