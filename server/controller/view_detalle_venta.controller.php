<?php


/**
*
*	Controller para la vista view_ventas
*
*	Contiene funciones que nos generan información relevante para la generación de gráficas de ventas
*	@author Luis Michel <luismichel@caffeina.mx>
*/

require_once("../server/model/view_detalle_venta.dao.php");


	/**
        *       Obtiene los datos del producto mas vendido. (Cantidad)
        *       Se obtienen nombre del producto, total del producto vendido.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange (Opcional) El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	function ProductoMasVendido( $timeRange, $fechaInicio, $fechaFinal )
	{
		$data = ViewDetalleVentaDao::getProductoMasVendido($timeRange, $fechaInicio, $fechaFinal);

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
        *       Obtiene los datos del producto mas vendido por sucursal. (Cantidad)
        *       Se obtienen nombre del producto, total en cantidad de lo que se ha vendido.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {Integer} id_sucursal El identificador de la sucursal de donde queremos obtener los datos
	*	@param {String} timeRange (Opcional) El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	function ProductoMasVendidoSucursal( $id_sucursal, $timeRange, $fechaInicio, $fechaFinal)
	{

		$data = ViewDetalleVentaDao::getProductoMasVendidoSucursal( $id_sucursal, $timeRange, $fechaInicio, $fechaFinal);

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


	/**
        *       Obtiene los datos del producto que genera mas ingresos. (Dinero)
        *       Se obtienen nombre del producto, total del ingreso del producto.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange (Opcional) El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */


	function ProductoIngresosTop( $timeRange, $fechaInicio, $fechaFinal )
	{
	
		$data = ViewDetalleVentaDao::getProductoIngresosTop( $timeRange, $fechaInicio, $fechaFinal);

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


	/**
        *       Obtiene los datos para generar una grafica de las ventas hechas de algun producto especifico.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {Integer} id_producto El identificador del producto del cual queremos obtener datos
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {String} tipo_venta (Opcional) El tipo de la venta, puede ser 'contado' o 'credito'
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos

        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */


	function DataVentasProducto($id_producto, $timeRange, $tipo_venta, $fechaInicio, $fechaFinal )
	{

		$data = ViewDetalleVentaDao::getDataVentasProducto( $id_producto, $timeRange, $tipo_venta, $fechaInicio, $fechaFinal);

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
        *       Obtiene los datos para generar una grafica de los productos mas vendidos en general.
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

	function DataProductosMasVendidos($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal)
	{

		$data = ViewDetalleVentaDao::getDataProductosMasVendidos( $timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal);

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



	switch($args['action'])
	{

		
		/*********VIEW_DETALLE_VENTA*************/

	case '501' : //productoMasVendido
		
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

		$result = ProductoMasVendido($timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;	

	case '502' : //productoMasVendidoSucursal

		
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_sucursal = null;

		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		
		if ( isset($args['id_sucursal']) )
		{
			$id_sucursal = $args['id_sucursal'];
		}

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		$result = ProductoMasVendidoSucursal( $id_sucursal, $timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '503' : //productoIngresoTop

	
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

		$result = ProductoIngresosTop( $timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '504' : //graficaVentasProducto
	
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_producto = null;
		$tipo_venta = null;

		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		
		if ( isset($args['id_producto']) )
		{
			$id_producto = $args['id_producto'];
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

		$result = DataVentasProducto( $id_producto, $timeRange, $tipo_venta, $fechaInicio, $fechaFinal);
		echo $result;
		break;


	case '505' : //graficaProductosMasVendidos
	
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

		$result = DataProductosMasVendidos($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal);
		echo $result;
		break;


	}

?>
