<?php

/**
 *	Controller para Ventas
 *
 *	Contiene todas las funciones que se conectan con la vista, generalmente JSON
 *	@author Rene Michel <rene@caffeina.mx>
 */
 
/**
 *
 */

/**
 *
 */
require_once('../server/model/sucursal.dao.php');
require_once('../server/misc/sanitize.php');



function getSelectData()
{
	$sucursales = SucursalDAO::getAll();


	$return_array = array();

	foreach($sucursales as $sucursal)
	{
		array_push($return_array, array("value"=>$sucursal->getIdSucursal(), "display"=>$sucursal->getDescripcion() ));
	}
	
	$return = " { \"success\": true, \"data\": ".json_encode($return_array)."}";
	return $return;
}

/**
*	Insertar sucursal
*
*/
function insertSucursal($descripcion, $direccion, $letrasFactura, $telefono, $rfc, $email)
{

	$sucursal = new Sucursal();
	
	$sucursal->setDescripcion($descripcion);
	$sucursal->setDireccion($direccion);
	$sucursal->setLetrasFactura($letrasFactura);
	$sucursal->setTelefono($telefono);
	$sucursal->setRfc($rfc);
	$sucursal->setEMail($email);
	
	return SucursalDAO::save($sucursal);
}


switch($args['action']){

	
	case '2201':
	
		echo getSelectData();
		
	break;
	
	case '2202': //insertar sucusal
	
		@$descripcion = $args['descripcion'];
		@$direccion = $args['direccion'];
		@$letrasFactura = $args['letras_factura'];
		@$telefono = $args['telefono'];
		@$rfc = $args['rfc'];
		@$email = $args['email'];
		
		/*
		* COMPROBAMOS QUE NO LOS DATOS NO TENGAN ESPACIOS BLANCOS AL INICIO O AL FINAL
		* O QUE SEAN CADENAS EN BLANCO, Y SE QUITAN LOS TAGS DE HTML
		*/
		$params = array(
					&$rfc,
					&$descripcion,
					&$direccion,
					&$telefono,
					&$email,
					&$letrasFactura
		);
		sanitize( $params );
		
		//===========================================
		
		try{
			if(insertSucursal($descripcion, $direccion, $letrasFactura, $telefono, $rfc, $email) == 1)
			{
				echo "{ \"success\": true, \"message\": \"Sucursal insertada correctamente\"}";
			}
			else
			{
				$result = "Occuri&oacute; un error al insertar la sucursal nueva, intente nuevamente";
				echo "{ \"success\": false, \"error\": \"$result\"}";
			}
			
		}catch(Exception $e){
			
			$result = "Occuri&oacute; un error al insertar la sucursal nueva, intente nuevamente";
			echo "{ \"success\": false, \"error\": \"$result\" }";
			
		}
	
	break;
	
	}

?>
