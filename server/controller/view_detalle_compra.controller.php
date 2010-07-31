<?php

/**
*
*	Controller para la vista view_detalle_compra
*
*	Contiene funciones que nos generan información relevante para la generación de gráficas de ventas
*	@author Luis Michel <luismichel@caffeina.mx>
*/


require_once('../server/model/view_detalle_compra.dao.php');


	/**
        *       Obtiene los datos para generar una grafica de los productos mas comprados en general.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {String} tipo_venta (Opcional) El tipo de la venta, puede ser 'contado' o 'credito'
	*	@param {Integer} id_sucursal (Opcional) El identificador de la sucursal de donde queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */


	function ProductosMasComprados($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal )
	{

		$data = ViewDetalleCompraDAO::getProductosMasComprados($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, "datos": '.json_encode($data).'}';
		}
		else
		{
			$result = '{ "success": false, "error": '.$data[1].'}';
		}

		return $result;


	}


	/**
        *       Obtiene los datos del producto en el que mas se gasta. (Dinero)
        *       Se obtienen nombre del producto, total del gasto del producto.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange (Opcional) El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */


	function ProductoGastoTop( $timeRange, $fechaInicio, $fechaFinal )
	{

		$data = ViewDetalleCompraDAO::getProductoGastoTop($timeRange, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, "error": '.$data[1].'}';
		}

		return $result;

	}



	switch($args['action'])
	{

		/******VIEW_DETALLE_COMPRA******************************************/

	case '701' : //productosMasComprados
	
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_sucursal = null;
		$tipo_venta = null;


		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		
		if ( isset($args['id_sucursal']) )
		{
			$id_sucursal = $args['id_sucursal'];
		}

		if ( isset($args['tipo_venta']) )
		{
			
			$tipo_venta = $args['tipo_venta'];
		}

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		$result = ProductosMasComprados($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal );
		echo $result;
		break;

	case '702' : //productoGastosTop

	
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;


		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		$result = ProductoGastoTop( $timeRange, $fechaInicio, $fechaFinal );
		echo $result;
		break;	
	


	}


?>
