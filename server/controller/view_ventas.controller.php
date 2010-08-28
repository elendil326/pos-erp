<?php

	
/**
*
*	Controller para la vista view_ventas
*
*	Contiene funciones que nos generan información relevante para la generación de gráficas de ventas
*	@author Luis Michel <luismichel@caffeina.mx>
*/

require_once("../server/model/view_ventas.dao.php");
require_once("../server/model/view_gastos.dao.php");
require_once("../server/model/pagos_venta.dao.php");
require_once("../server/model/sucursal.dao.php");



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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
	
	function SucursalVentasTop( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal, $showAll )
	{
		$data = ViewVentasDao::getSucursalVentasTop($timeRange, $fechaInicio, $fechaFinal, $id_sucursal);

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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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

	function VendedorMasProductivoSucursal( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal, $showAll, $formatoGrafica )
	{
		
		$data = ViewVentasDao::getVendedorMasProductivoSucursal($timeRange, $id_sucursal, $fechaInicio, $fechaFinal, $formatoGrafica );

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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';
		}
		else
		{
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
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
			$result = '{ "success": false, "error": "'.$data[1].'"}';
		}

		return $result;	
	}


	/**
	*	getDataVentasGastosAbonos
	*
	*	Obtiene las ventas totales, los gastos y los abonos de una sucursal en un determinado tiempo.
	*	Se pueden obtener las utilidades con estos datos.
	*
	*	@param <Integer> id_sucursal El id de la sucursal de la cual se quieren obtener los datos
	*	@param <fechaInicio> fechaInicio Fecha de inicio de un período de tiempo del cual se quieren analizar los datos. Debe de ir junto con fechaFinal.
	*	@param <fechaFinal> fechaFinal Fecha final de un período de tiempo del cual se quieren analizar los datos. Debe de ir junto con fechaInicio.
	*
	*/
	
	function getDataVentasGastosAbonos( $id_sucursal, $fechaInicio, $fechaFinal )
	{
		
		if ( $id_sucursal != NULL )
		{

			$dataVentas = ViewVentasDao::getSucursalVentasTop(NULL, $fechaInicio, $fechaFinal, $id_sucursal);
			$dataGastos = ViewGastosDao::gastosSucursal( NULL, $fechaInicio, $fechaFinal, $id_sucursal );
			$dataAbonos = PagosVentaDAO::abonosSucursal( $id_sucursal, $fechaInicio, $fechaFinal );
			
			$ventasTotales = 0;
			$gastosTotales = 0;
			$abonosTotales = 0;
			
			if ( $dataVentas[0] == "No se encontraron datos" )
			{
				$ventasTotales = 0;
			}
			else
			{
				$ventasTotales = $dataVentas[0]['cantidad'];
			}
			
			if ( $dataGastos[0] == "No se encontraron datos" )
			{
				$gastosTotales = 0;
			}
			else
			{
				$gastosTotales = $dataGastos[0]['cantidad'];
			}
			
			
			if ( count($dataAbonos[0]) == 1  )
			{
				$abonosTotales = 0; 
			}
			else
			{
				$abonosTotales = $dataAbonos[0]['abono'];
			}
			
			$sucursal = new Sucursal();
			$sucursal->setIdSucursal($id_sucursal);
			$datosSucursal = SucursalDAO::search($sucursal);
		
			
			
			$arrayResults = array();
			array_push( $arrayResults, array( "id_sucursal" => $id_sucursal, "sucursal" => $datosSucursal[0]->getDescripcion(), "venta_total" => $ventasTotales, "gasto_total" => $gastosTotales, "abono_total" => $abonosTotales ) );
			
			/*var_dump( $dataVentas );
			echo "<br>";
			var_dump( $dataGastos );
			echo "<br>";
			var_dump( $dataAbonos );
			echo "<br>";*/
			
			$result = '{ "success": true, "datos": '.json_encode($arrayResults).'}';
			
			return $result;
		}
		else
		{
	
			$allSucursales = SucursalDAO::getAll();
			$ventasTotales = 0;
			$gastosTotales = 0;
			$abonosTotales = 0;
			for ( $i=0 ; $i < count( $allSucursales ) ; $i++ )
			{
			
			
				$id_sucursal = $allSucursales[$i]->getIdSucursal();
				
				$dataVentas = ViewVentasDao::getSucursalVentasTop(NULL, $fechaInicio, $fechaFinal, $id_sucursal);
				$dataGastos = ViewGastosDao::gastosSucursal( NULL, $fechaInicio, $fechaFinal, $id_sucursal );
				$dataAbonos = PagosVentaDAO::abonosSucursal( $id_sucursal, $fechaInicio, $fechaFinal );
				
				
				
				if ( $dataVentas[0] == "No se encontraron datos" )
				{
					$ventasTotales += 0;
				}
				else
				{
					$ventasTotales += $dataVentas[0]['cantidad'];
				}
				
				if ( $dataGastos[0] == "No se encontraron datos" )
				{
					$gastosTotales += 0;
				}
				else
				{
					$gastosTotales += $dataGastos[0]['cantidad'];
				}
				
				
				if ( count($dataAbonos[0]) == 1  )
				{
					$abonosTotales += 0; 
				}
				else
				{
					$abonosTotales += $dataAbonos[0]['abono'];
				}
				
				
			}
			$sucursal = new Sucursal();
			$sucursal->setIdSucursal($id_sucursal);
			$datosSucursal = SucursalDAO::search($sucursal);
		
			
			
			$arrayResults = array();
			array_push( $arrayResults, array( "id_sucursal" => $id_sucursal, "sucursal" => $datosSucursal[0]->getDescripcion(), "venta_total" => $ventasTotales, "gasto_total" => $gastosTotales, "abono_total" => $abonosTotales ) );
			
			/*var_dump( $dataVentas );
			echo "<br>";
			var_dump( $dataGastos );
			echo "<br>";
			var_dump( $dataAbonos );
			echo "<br>";*/
			
			$result = '{ "success": true, "datos": '.json_encode($arrayResults).'}';
			
			return $result;
		
		
		
		}
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
		$showAll = null;
		$id_sucursal = null;

		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}
		
		if ( isset( $args['showAll']) )
		{
			$showAll = $args['showAll'];
		}

		if ( isset ( $args['id_sucursal']) )
		{
			$id_sucursal = $args['id_sucursal'];
		}
		
		$result = SucursalVentasTop($timeRange, $fechaInicio, $fechaFinal, $id_sucursal, $showAll);
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
		$formatoGrafica = null;

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
		
		if ( isset($args['formatoGrafica']) )
		{
			$formatoGrafica = $args['formatoGrafica'];
		}
		

		$result = VendedorMasProductivoSucursal($timeRange, $id_sucursal, $fechaInicio, $fechaFinal, $showAll, $formatoGrafica);
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
		
	case '412' :
	
		$id_sucursal = NULL;
	
		if ( !isset( $args['fecha-inicio'] ) && !isset( $args['fecha-final'] ) )
		{
			echo ' { "success": false, "error": "Faltan parametros" } ';
			return;
		}
	
		if ( isset( $args['id_sucursal'] ) )
		{
			$id_sucursal = $args['id_sucursal'];
		}
	

		$fechaInicio = $args['fecha-inicio'];
		$fechaFinal = $args['fecha-final'];
		
		$result = getDataVentasGastosAbonos( $id_sucursal, $fechaInicio, $fechaFinal );
		echo $result;
		return;
		
		
	break;

	}

?>
