<?php

	
/**
*
*	Controller para la vista view_compras
*
*	Contiene funciones que nos generan información relevante para la generación de gráficas de compras
*	@author Luis Michel <luismichel@caffeina.mx>
*/

require_once('../server/model/view_compras.dao.php');


	/**
        *       Obtiene los datos para generar una grafica de las compras en general.
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


	function DataCompras($timeRange, $id_sucursal, $fechaInicio, $fechaFinal)
	{	
		
		$data = ViewComprasDao::getDataCompras($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = "{ \"success\": true, \"datos\": ".json_encode($data)."}";
		}
		else
		{
			$result = "{ \"success\": false, \"error\": ".$data[1]."}";
		}

		return $result;

	}


	/**
        *       Obtiene los datos para generar una grafica de las compras al contado.
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


	function DataComprasContado( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
	{

		$data = ViewComprasDao::getDataComprasContado($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = "{ \"success\": true, \"datos\": ".json_encode($data)."}";
		}
		else
		{
			$result = "{ \"success\": false, \"error\": ".$data[1]."}";
		}

		return $result;

	}



	/**
        *       Obtiene los datos para generar una grafica de las compras a credito.
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


	function DataComprasCredito( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
	{


		$data = ViewComprasDao::getDataComprasCredito($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = "{ \"success\": true, \"datos\": ".json_encode($data)."}";
		}
		else
		{
			$result = "{ \"success\": false, \"error\": ".$data[1]."}";
		}

		return $result;


	}


	/**
        *       Obtiene los datos para generar una grafica de las compras que se han hecho a algun proveedor.
        *       Se obtiene un valor x, un valor y, y un label.
        *
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param {String} timeRange El rango de tiempo para formatear el resultado (semana, mes, year)
	*	@param {Integer} id_proveedor El identificador del proveedor de quien queremos obtener los datos
	*	@param {Date} fechaInicio (Opcional) La fecha de inicio de donde se quieren ver los datos
	*	@param {Date} fechaFinal (Opcional) La fecha final del rango de tiempo de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de la consulta NOTA: Si existe un error regresara un arreglo, donde el primer
	*			elemento del arreglo es FALSE, y el segundo la razon del error
        */

	
	function DataComprasProveedor($timeRange, $id_proveedor, $fechaInicio, $fechaFinal)
	{

		$data = ViewComprasDao::getDataComprasProveedor($timeRange, $id_proveedor, $fechaInicio, $fechaFinal);

		if ( $data[0] != false )
		{
			$result = "{ \"success\": true, \"datos\": ".json_encode($data)."}";
		}
		else
		{
			$result = "{ \"success\": false, \"error\": ".$data[1]."}";
		}

		return $result;

	}
	
	
	function getDataGridCompras($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente)
	{
	
		$total = count(ViewComprasDao::getAll());
		$compras = ViewComprasDao::getAll($page,$rp,$sortname, $sortorder);
		
		$rows_array = array();
		foreach($compras as $compra)
		{
			$row = array(	$compra->getIdCompra(), 
					$compra->getProveedor(), 
					$compra->getTipoCompra(), 
					$compra->getFecha(), 
					$compra->getSubtotal(),
					$compra->getIva(),
					$compra->getSucursal(),
					$compra->getUsuario()
					);
					
			array_push($rows_array, array( "id"=>$compra->getIdCompra(), "cell" => $row ));
		}
		
		$array_result = '{ "page": '.$page.', "total": '.$total.', "rows" : '.json_encode($rows_array).'}';
			
		echo $array_result;
	}
	
	
	
	function getDataGridComprasContado($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente)
	{
	
		//$total = count(ViewComprasDao::getAll());
		$compras = ViewComprasDao::getAll(null,null,$sortname, $sortorder);
		
		$compras_contado = array();
		$total = 0;
		foreach($compras as $compra)
		{
			if($compra->getTipoCompra() == "contado")
			{
				array_push($compras_contado, $compra);
				$total++;
			}
		}
		
		
		
		$rows_array = array();
		
		$counter = 0;
		
		foreach($compras_contado as $compra_contado)
		{
		
			if( ($counter >= ($page-1)*$rp) && ( $counter < ($page*$rp)) )
			{
				$row = array(	$compra_contado->getIdCompra(), 
						$compra_contado->getProveedor(), 
						$compra_contado->getTipoCompra(), 
						$compra_contado->getFecha(), 
						$compra_contado->getSubtotal(),
						$compra_contado->getIva(),
						$compra_contado->getSucursal(),
						$compra_contado->getUsuario()
						);
					
				array_push($rows_array, array( "id"=>$compra_contado->getIdCompra(), "cell" => $row ));
			}
			
			if( $counter > ($page*$rp))
			{
				break;
			}
			
			$counter++;
		}
		
		$array_result = '{ "page": '.$page.', "total": '.$total.', "rows" : '.json_encode($rows_array).'}';
			
		echo $array_result;
	}
	
	
	function getDataGridComprasCredito($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente)
	{
	
		//$total = count(ViewComprasDao::getAll());
		$compras = ViewComprasDao::getAll(null,null,$sortname, $sortorder);
		
		$compras_contado = array();
		$total = 0;
		foreach($compras as $compra)
		{
			if($compra->getTipoCompra() == "credito")
			{
				array_push($compras_contado, $compra);
				$total++;
			}
		}
		
		
		
		$rows_array = array();
		
		$counter = 0;
		
		foreach($compras_contado as $compra_contado)
		{
		
			if( ($counter >= ($page-1)*$rp) && ( $counter < ($page*$rp)) )
			{
				$row = array(	$compra_contado->getIdCompra(), 
						$compra_contado->getProveedor(), 
						$compra_contado->getTipoCompra(), 
						$compra_contado->getFecha(), 
						$compra_contado->getSubtotal(),
						$compra_contado->getIva(),
						$compra_contado->getSucursal(),
						$compra_contado->getUsuario()
						);
					
				array_push($rows_array, array( "id"=>$compra_contado->getIdCompra(), "cell" => $row ));
			}
			
			if( $counter > ($page*$rp))
			{
				break;
			}
			
			$counter++;
		}
		
		$array_result = '{ "page": '.$page.', "total": '.$total.', "rows" : '.json_encode($rows_array).'}';
			
		echo $array_result;
	}



	switch( $args['action'] )
	{

		/********VIEW_COMPRAS*****************************************/

	case '601': //graficaCompras
	
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

		$result = DataCompras($timeRange, $id_sucursal, $fechaInicio, $fechaFinal);
		echo $result;
		break;


	case '602' : //graficaComprasContado
	
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

		$result = DataComprasContado( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal );
		echo $result;
		break;

	case '603' : //graficaComprasCredito
	
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

		$result = DataComprasCredito( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal );
		echo $result;
		break;


	case '604': //graficaComprasProveedor
	
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_proveedor = null;


		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		
		if ( isset($args['id_proveedor']) )
		{
			$id_proveedor = $args['id_proveedor'];
		}


		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		$result = DataComprasProveedor( $timeRange, $id_proveedor, $fechaInicio, $fechaFinal );
		echo $result;
		break;


	case '605': // getDataGridCompras
		
		$page = $args['page'];
		$rp = $args['rp'];
		$sortname = $args['sortname'];
		$sortorder = $args['sortorder'];
		
		echo getDataGridCompras($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente);
	
		break;
		
		
	case '606': // getDataGridComprasContado
	
		@$page = $args['page'];
		@$rp = $args['rp'];
		@$sortname = $args['sortname'];
		@$sortorder = $args['sortorder'];
		
		echo @getDataGridComprasContado($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente);
	
		break;
		
	case '607': // getDataGridComprasContado
	
		$page = $args['page'];
		$rp = $args['rp'];
		$sortname = $args['sortname'];
		$sortorder = $args['sortorder'];
		
		echo getDataGridComprasCredito($page,$rp, $sortname, $sortorder, $search, $qtype, $de, $al, $id_cliente);
	
		break;

	}



?>
