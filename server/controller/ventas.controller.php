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
require_once('../server/model/ventas.dao.php');


/**
*	Funcion para obtener los datos de ventas por clientes con el formato JSON necesario
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*/
function getGridDataVentasPorClientes($page,$rp,$sortname,$sortorder,$search,$qtype){

	
	$ventas = VentasDAO::getVentasPorClientes_grid($page,$rp,$sortname,$sortorder,$search,$qtype);
	
	//$array_result = '{ "page": '.$page.', "total": '.count($ventas).', "rows" : '.json_encode($ventas).'}';
	$array_result = '{ "page": '.$page.', "total": '.$ventas['total'].', "rows" : '.json_encode($ventas['data']).'}';
	return $array_result;

}


/**
*	Funcion para obtener los datos de ventas a credito por clientes con el formato JSON necesario
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*	@see	getVentasACreditoPorClientes_grid
*/
function getGridDataVentasACreditoPorClientes($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al,$id_cliente){

	$ventas = VentasDAO::getVentasACreditoPorClientes_grid($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al,$id_cliente);
	
	
	
	//$array_result = '{ "page": '.$page.', "total": '.count($ventas).', "rows" : '.json_encode($ventas).'}';
	$array_result = '{ "page": '.$page.', "total": '.$ventas['total'].', "rows" : '.json_encode($ventas['data']).'}';
	return $array_result;

}

/**
*	Funcion para obtener los datos de ventas de contado por clientes con el formato JSON necesario
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*/
function getGridDataVentasDeContadoPorClientes($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente){


	$ventas = VentasDAO::getVentasDeContadoPorClientes_grid($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente);
	
	//$array_result = '{ "page": '.$page.', "total": '.count($ventas).', "rows" : '.json_encode($ventas).'}';
	$array_result = '{ "page": '.$page.', "total": '.$ventas['total'].', "rows" : '.json_encode($ventas['data']).'}';
	return $array_result;

}


switch($args['action']){

	
	case '301': //'getGridDataVentasPorClientes'
	
		@$rp = strip_tags($args['rp']);
		@$sortname = strip_tags($args['sortname']);
		@$sortorder = strip_tags($args['sortorder']);
		
		if(isset($args['query']) && !empty($args['query']))
		{
		        $search = strip_tags($args['query']);
		        @$qtype = strip_tags($args['qtype']);
		}

		if(isset($args['page']))
		{
			$page = strip_tags($args['page']);
		}
		else{
			$page = 1;
		}
		$ans = getGridDataVentasPorClientes($page,$rp,$sortname,$sortorder,$search,$qtype);
		echo $ans;
		break;
		
	
	case '302': //'getGridDataVentasACreditoPorClientes'
	
		
	
		@$id_cliente=$args['id_cliente'];
		@$de=$args['de'];
		@$al=$args['al'];
		
		@$page = strip_tags($args['page']);
		@$rp = strip_tags($args['rp']);
		@$sortname = strip_tags($args['sortname']);
		@$sortorder = strip_tags($args['sortorder']);
		
		if(isset($args['query']) && !empty($args['query']))
		{
		        $search = strip_tags($args['query']);
		        @$qtype = strip_tags($args['qtype']);
		}
		
		//Si no se envia el dato de page, significa que estamos en la 1
		if(isset($args['page']))
		{
			$page = strip_tags($args['page']);
		}
		else{
			$page = 1;
		}

		$ans = getGridDataVentasACreditoPorClientes($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al,$id_cliente);
		echo $ans;
		break;
		
	
	case '303': //'getGridDataVentasDeContadoPorClientes'
	
		
	
		@$id_cliente=$args['id_cliente'];
		@$de=$args['de'];
		@$al=$args['al'];
		
		@$page = strip_tags($args['page']);
		@$rp = strip_tags($args['rp']);
		@$sortname = strip_tags($args['sortname']);
		@$sortorder = strip_tags($args['sortorder']);
		
		if(isset($args['query']) && !empty($args['query']))
		{
		        $search = strip_tags($args['query']);
		        @$qtype = strip_tags($args['qtype']);
		}
		
		//Si no se envia el dato de page, significa que estamos en la 1
		if(isset($args['page']))
		{
			$page = strip_tags($args['page']);
		}
		else{
			$page = 1;
		}

		
		$ans = getGridDataVentasDeContadoPorClientes($page,$rp,$sortname,$sortorder,$search,$qtype, $de, $al,$id_cliente);
		echo $ans;
	
		break;


}
