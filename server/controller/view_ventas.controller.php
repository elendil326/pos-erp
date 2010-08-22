<?php

	
/**
*
*	Controller para la vista view_ventas
*
*	Contiene funciones que nos generan información relevante para la generación de gráficas de ventas
*	@author Luis Michel <luismichel@caffeina.mx>
*/

require_once("../server/model/view_ventas.dao.php");


	/**
        *       Obtiene los datos del vendedor más productivo. (Dinero)
        *       Se obtienen nombre del vendedor, total de lo que ha vendido.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return String JSON con los datos obtenidos de la consulta
        */


	function VendedorMasProductivo( $timeRange, $fechaInicio, $fechaFinal, $showAll )
	{
		$data = ViewVentasDao::getVendedorMasProductivo($timeRange, $fechaInicio, $fechaFinal);

		
		if ( $data[0] != false )
		{
			if ( $showAll == true )
			{
				$result = '{ "success": true, "datos": '.json_encode($data).'}';
			}
			else
			{
				$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
			}
		}
		else
		{
			$result = '{ "success": false, "error": '.$data[1].'}';
		}

		return $result;
	}


/**
        *       Obtiene los datos de la sucursal que mas vende. (Cantidad)
        *       Se obtienen nombre de la sucursal, total del producto vendido.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */
	
	function SucursalVentasTop( $timeRange, $fechaInicio, $fechaFinal )
	{
		$data = ViewVentasDao::getSucursalVentasTop($timeRange, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, datos: '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, error: '.$data[1].'}';
		}

		return $result;
	}


	/**
        *       Obtiene los datos del cliente que compra mas. (Cantidad)
        *       Se obtienen nombre del cliente, total de lo que ha comprado.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	function ClienteComprasTop( $timeRange, $fechaInicio, $fechaFinal )
	{

		$data = ViewVentasDao::getClienteComprasTop($timeRange, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, datos: '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, error: '.$data[1].'}';
		}

		return $result;		
	}


	/**
        *       Obtiene los datos del vendedor que vende mas por sucursal. (Dinero)
        *       Se obtienen nombre del vendedor, total de lo que ha vendido.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_sucursal El identificador de la sucursal de donde queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	function VendedorMasProductivoSucursal( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal, $showAll )
	{
		
		$data = ViewVentasDao::getVendedorMasProductivoSucursal($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			if ( $showAll == true )
			{
				$result = '{ "success": true, "datos": '.json_encode($data).'}';
			}
			else
			{
				$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
			}
		}
		else
		{
			$result = '{ "success": false, error: '.$data[1].'}';
		}

		return $result;	
		
	}


	/**
        *       Obtiene los datos del cliente que compra mas por sucursal. (Cantidad)
        *       Se obtienen nombre del cliente, total de lo que ha comprado.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_sucursal El identificador de la sucursal de donde queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */


	function ClienteComprasTopSucursal( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal)
	{
		
		$data = ViewVentasDao::getClienteComprasTopSucursal($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, datos: '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, error: '.$data[1].'}';
		}

		return $result;	
			
	}


	/**
        *       Obtiene los datos del cliente que compra mas a credito. (Cantidad)
        *       Se obtienen nombre del cliente, total de lo que ha comprado.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange (Opcional) El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	function ClienteComprasCreditoTop( $timeRange, $fechaInicio, $fechaFinal )
	{

		$data = ViewVentasDao::getClienteComprasCreditoTop($timeRange, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, datos: '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, error: '.$data[1].'}';
		}

		return $result;	
	}


	/**
        *       Obtiene los datos del cliente que compra mas al contado. (Cantidad)
        *       Se obtienen nombre del cliente, total de lo que ha comprado.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange (Opcional) El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	function ClienteComprasContadoTop( $timeRange, $fechaInicio, $fechaFinal )
	{

		$data = ViewVentasDao::getClienteComprasContadoTop($timeRange, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = '{ "success": true, datos: '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, error: '.$data[1].'}';
		}

		return $result;	
		
	}


	/**
        *       Obtiene los datos para generar una grafica de las ventas en general.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_sucursal (Opcional) El identificador de la sucursal de donde queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */

	function DataVentas( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
	{

		$data = ViewVentasDao::getDataVentas($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

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
        *       Obtiene los datos para generar una grafica de las ventas al contado.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_sucursal (Opcional) El identificador de la sucursal de donde queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */

	
	function DataVentasContado( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
	{

		$data = ViewVentasDao::getDataVentasContado($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

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
        *       Obtiene los datos para generar una grafica de las ventas a credito.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_sucursal (Opcional) El identificador de la sucursal de donde queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */

	function DataVentasCredito($timeRange, $id_sucursal, $fechaInicio, $fechaFinal)
	{

		$data = ViewVentasDao::getDataVentasCredito($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

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
        *       Obtiene los datos para generar una grafica de las ventas hechas a algun cliente especifico.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_cliente El identificador del cliente de quien queremos obtener datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */

	function DataVentasCliente($timeRange, $id_cliente, $fechaInicio, $fechaFinal)
	{

		$data = ViewVentasDao::getDataVentasCliente($timeRange, $id_cliente, $fechaInicio, $fechaFinal);

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
		
		/*********VIEW_VENTAS*************/
	case '401' :
		
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$showAll = null;
		
		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		if ( isset($args['showAll']) )
		{
			$showAll = $args['showAll'];
		}
		else
		{
			$showAll = false;
		}
		
		$result = VendedorMasProductivo($timeRange, $fechaInicio, $fechaFinal, $showAll);
		echo $result;
		break;

	case '402' :
		

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

		$result = SucursalVentasTop($timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;
	
	case '403' :
		

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

		$result = ClienteComprasTop($timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '404' :

		
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_sucursal = null;
		$showAll = null;

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
		
		if ( isset($args['showAll']) )
		{
			$showAll = $args['showAll'];
		}
		else
		{
			$showAll = false;
		}
		

		$result = VendedorMasProductivoSucursal($timeRange, $id_sucursal, $fechaInicio, $fechaFinal, $showAll);
		echo $result;
		break;


	case '405' :


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

		$result = ClienteComprasTopSucursal($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '406' :


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

		$result = ClienteComprasCreditoTop($timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '407' :


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

		$result = ClienteComprasContadoTop($timeRange, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '408' :
		

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

		$result = DataVentas($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);
		echo $result;
		break;

	case '409' :
	
		
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

		$result = DataVentasContado($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);
		echo $result;
		break;		

	case '410' :

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

		$result = DataVentasCredito($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);
		echo $result;
		break;	

	case '411' :

		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_cliente = null;

		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		
		if ( isset($args['id_cliente']) )
		{
			$id_cliente = $args['id_cliente'];
		}

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		$result = DataVentasCliente($timeRange, $id_cliente, $fechaInicio, $fechaFinal);
		echo $result;
		break;			

	}

?>
