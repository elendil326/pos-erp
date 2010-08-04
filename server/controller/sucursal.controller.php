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


switch($args['action']){

	
	case '2201':
	
		echo getSelectData();
		
	break;
	
	}

?>
