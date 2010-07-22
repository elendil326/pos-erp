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
require_once('../model/ventas.vo.php');

/**
 *
 */
require_once('../model/ventas.dao.php');


/**
*	Funcion para obtener los datos de ventas por clientes con el formato JSON necesario
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*/
function getGridDataVentasPorClientes(){

	$page = strip_tags($_POST['page']);
        $rp = strip_tags($_POST['rp']);
        $sortname = strip_tags($_POST['sortname']);
        $sortorder = strip_tags($_POST['sortorder']);
        
        if(isset($_POST['query']) && !empty($_POST['query']))
        {
                $search = strip_tags($_POST['query']);
                $qtype = strip_tags($_POST['qtype']);
	}


	$ventas = VentasDAO::getVentasPorClientes_grid($page,$rp,$sortname,$sortorder,$search,$qtype);
	
	//Si no se envia el dato de page, significa que estamos en la 1
	if(isset($_POST['page']))
	{
		$page = strip_tags($_POST['page']);
	}
	else{
		$page = 1;
	}
	
	$array_result = '{ "page": '.$page.', "total": '.count($ventas).', "rows" : '.json_encode($ventas).'}';
	echo $array_result;

}


/**
*	Funcion para obtener los datos de ventas a credito por clientes con el formato JSON necesario
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*	@see	getVentasACreditoPorClientes_grid
*/
function getGridDataVentasACreditoPorClientes(){

	$id_cliente=$_REQUEST['id_cliente'];
        $de=$_REQUEST['de'];
        $al=$_REQUEST['al'];

	$ventas = VentasDAO::getVentasACreditoPorClientes_grid($id_cliente, $de, $al);
	
	//Si no se envia el dato de page, significa que estamos en la 1
	if(isset($_POST['page']))
	{
		$page = strip_tags($_POST['page']);
	}
	else{
		$page = 1;
	}
	
	$array_result = '{ "page": '.$page.', "total": '.count($ventas).', "rows" : '.json_encode($ventas).'}';
	echo $array_result;

}

/**
*	Funcion para obtener los datos de ventas de contado por clientes con el formato JSON necesario
*	para que sea leido por el Flexigrid
*
*	@author Rene Michel <rene@caffeina.mx>
*	@return	String JSON con los datos formateados para Flexigrid	
*/
function getGridDataVentasDeContadoPorClientes(){

	$id_cliente=$_REQUEST['id_cliente'];
        $de=$_REQUEST['de'];
        $al=$_REQUEST['al'];

	$ventas = VentasDAO::getVentasDeContadoPorClientes_grid($id_cliente, $de, $al);
	
	//Si no se envia el dato de page, significa que estamos en la 1
	if(isset($_POST['page']))
	{
		$page = strip_tags($_POST['page']);
	}
	else{
		$page = 1;
	}
	
	$array_result = '{ "page": '.$page.', "total": '.count($ventas).', "rows" : '.json_encode($ventas).'}';
	echo $array_result;

}


?>
