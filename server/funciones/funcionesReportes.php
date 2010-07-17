<?php

	
	/*******************************************************************************
	 REPORTES DEL TIPO "MEJOR X DE LA SUCURSAL Y" O "MEJOR X DE TODAS LAS SUCURSALES"
	*******************************************************************************/

	/*
	*	UTILS PARA LOS REPORTES
	*/ 

	//Obtenemos dos fechas dentro de un rango (semana, mes, año). Se refiere la ultima semana, mes, año.
	function getDateRange($dateInterval){

		$datesArray = array();
		$currentDate = getdate();
		$dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];

		switch($dateInterval)
			{
				case 'semana': 	$fecha = date_create( $dateToday );
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('7 days'));
						$dateWeekBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateWeekBefore);
						array_push($datesArray, $dateToday);
	
						return($datesArray);

						break;
				/************************************************************************/

				case 'mes':	$fecha = date_create( $dateToday );
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('1 month'));
						$dateWeekBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateWeekBefore);
						array_push($datesArray, $dateToday);

						return($datesArray);

						break;
				/************************************************************************/

				case 'año':	$fecha = date_create( $dateToday );
						//$fecha = date_create("2010-07-14");
						date_sub($fecha, date_interval_create_from_date_string('1 year'));
						$dateWeekBefore = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateWeekBefore);
						array_push($datesArray, $dateToday);

						return($datesArray);

						break;
				/************************************************************************/

				default:	return false;
			}

	}

	/*
	*	VENDEDOR MAS PRODUCTIVO EN GENERAL
	*/

	function vendedorMasProductivo(){


		$dateRange = "";
		$params = array();

		$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` ";				


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}


		//Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']) )
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `id_usuario` ORDER BY `Vendido` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	/*
	*	PRODUCTO MAS VENDIDO EN GENERAL
	*/	
	
	function productoMasVendido(){
		
		
		$dateRange = "";
		$params = array();

			
		$qry_select = " SELECT `inventario`.`denominacion` AS `nombre`, SUM(`detalle_venta`.`cantidad`) AS `Cantidad` FROM `inventario`, `detalle_venta`, `ventas`  WHERE `inventario`.`id_producto` = `detalle_venta`.`id_producto` AND `detalle_venta`.`id_venta` = `ventas`.`id_venta`";


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}


		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `detalle_venta`.`id_producto` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	/*
	*	SUCURSAL QUE VENDE MAS 
	*/

	function sucursalVentasTop(){
		
		$dateRange = "";
		$params = array();

			
		$qry_select = " SELECT `sucursal`.`descripcion` AS `nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `sucursal` WHERE `sucursal`.`id_sucursal` = `ventas`.`sucursal` ";

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}

			
		}

		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`sucursal` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;
		
	}

	/*
	*	CLIENTE QUE COMPRA MAS
	*/

	function clienteComprasTop(){
	
		$dateRange = "";
		$params = array();

			
		$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` ";

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}


		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`id_cliente` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;
	}


	/*
	*	VENDEDOR MAS PRODUCTIVO EN UNA SUCURSAL ESPECIFICA
	*/

	function vendedorMasProductivoSucursal(){

		if ( !isset($_REQUEST['id_sucursal']) )
		{
			fail("Faltan parametros");
			return;
		}
		
		$id_sucursal = $_REQUEST['id_sucursal'];
		$dateRange = "";
		$params = array($id_sucursal);

		$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` AND `ventas`.`sucursal` = ?";				


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}
			
			
		}

		//Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']))
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`id_usuario` ORDER BY `Vendido` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	/*
	*	PRODUCTO MAS VENDIDO EN UNA SUCURSAL
	*/

	function productoMasVendidoSucursal(){
		

		if ( !isset($_REQUEST['id_sucursal']) )
		{
			fail("Faltan parametros");
			return;
		}
		
		$id_sucursal = $_REQUEST['id_sucursal'];
		
		$dateRange = "";
		$params = array($id_sucursal);

			
		$qry_select = " SELECT `inventario`.`denominacion`, SUM(`detalle_venta`.`cantidad`) AS `Cantidad` FROM `inventario`, `detalle_venta`, `ventas`  WHERE `inventario`.`id_producto` = `detalle_venta`.`id_producto` AND `detalle_venta`.`id_venta` = `ventas`.`id_venta` AND `ventas`.`sucursal` = ?";


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}

			
		}

		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']) )
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `detalle_venta`.`id_producto` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;

	}

	
	/*
	*	CLIENTE QUE COMPRA MAS EN UNA SUCURSAL
	*/

	function clienteComprasTopSucursal(){
	
		if ( !isset($_REQUEST['id_sucursal']) )
		{
			fail("Faltan parametros");
			return;
		}
		
		$id_sucursal = $_REQUEST['id_sucursal'];
		
		$dateRange = "";
		$params = array($id_sucursal);

			
		$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";


		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];


			//Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)	
			$datesArray = getDateRange($dateInterval);	

			if( $datesArray != false )
			{
				array_push($params, $datesArray[0]);
				array_push($params, $datesArray[1]);

				$qry_select .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			}

			
		}

		if ( isset($_REQUEST['fecha-inicio']) && isset($_REQUEST['fecha-fin']) && !isset($_REQUEST['dateRange']) )
		{
			array_push($params, $_REQUEST['fecha-inicio']);
			array_push($params, $_REQUEST['fecha-fin']);
			$dateRange .= " AND date(`ventas`.`fecha`) BETWEEN ? AND ?";
			$qry_select .= $dateRange;
		}

		$qry_select .= " GROUP BY `ventas`.`id_cliente` ORDER BY `Cantidad` DESC LIMIT 1";

		$listar = new listar($qry_select, $params);
		echo $listar->lista();

		return;
	}


	/***************************************************************************************************
				REPORTES PARA GRAFICAS
	***************************************************************************************************/

	/*
	*	UTILS PARA LOS REPORTES
	*/ 

	//Obtenemos dos fechas dentro de un rango (semana, mes, año). Se refiere la ultima semana, mes, año.
	function getDateRangeGraphics($dateInterval){

		$datesArray = array();
		$params = array();
		$currentDate = getdate();
		$dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];


		//**** ESTE SWITCH NOS REGRESA UN ARREGLO CON TODAS LAS FECHAS QUE QUEREMOS ANALIZAR******//
		switch($dateInterval)
			{
				case 'semana': 	
												
						//Hacemos esto porque el valor numerico para el domingo es 0, nosotros lo tomamos como 7
						if ( $currentDate['wday'] == 0)
						{
							$weekControl = 7;
						}
						else
						{
							$weekControl = $currentDate['wday'];
						}

						$daysDelta = $weekControl - 1; //Diferencia de dias que hay entre hoy y el lunes(1)
						
						$fecha = date_create( $dateToday );
						//Obtenemos la fecha del lunes de esa semana usando $daysDelta
						date_sub( $fecha, date_interval_create_from_date_string( $daysDelta.' days') ); 
						$dateMonday = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateMonday);

						//Obtenemos la fecha de hoy y la mandamos a nuestro arreglo de fechas
						$fecha = date_create( $dateToday );
						$today = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $today);


						break;
				/************************************************************************/

				case 'mes':	
						//Obtenemos la fecha del inicio de mes
						$startOfMonth = date_create( $currentDate['year'].'-'.$currentDate['mon'].'-01' );
						$startDate = date_format($startOfMonth, 'Y-m-d');
						array_push($datesArray, $startDate);

						//Obtenemos la fecha de hoy y la agregamos a nuestro arreglo de fechas
						$fecha = date_create( $dateToday );
						$today = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $today);
						
						break;

				/************************************************************************/

				case 'year':	
						//Obtenemos la fecha del primer dia del año y lo metemos a un arreglo de fechas
						$fecha = date_create($currentDate['year'].'-01-01'); 
						$dateStart = date_format($fecha, 'Y-m-d');
						array_push($datesArray, $dateStart);


						$fecha = date_create( $dateToday );
						array_push($datesArray, date_format($fecha, 'Y-m-d')); //Agregamos la fecha de hoy porque sacara lo que lleva del mes actual
						
						

						break;
				/************************************************************************/

				default:	$datesArray = false;

			}
		return $datesArray;
}



	function betweenDatesQueryPart( $dateInterval, $table )
	{


				/*
				*	FORMAMOS EL QUERY PARA LA SEMANA
				*/
				if ( $dateInterval == 'semana')
				{
					$qry_select = " date(`".$table."`.`fecha`) BETWEEN ? AND ?";
	
					$qry_select .= " GROUP BY DAYOFWEEK(`".$table."`.`fecha` )";
				}

				/*
				*	FORMAMOS EL QUERY PARA EL MES
				*/

				if ( $dateInterval == 'mes')
				{
					$qry_select = " date(`".$table."`.`fecha`) BETWEEN ? AND ?";

					$qry_select .= " GROUP BY DAYOFMONTH(`".$table."`.`fecha` )";


				}
				//var_dump($params);

				if ($dateInterval == 'year')
				{
					$qry_select = " date(`".$table."`.`fecha`) BETWEEN ? AND ?";

					$qry_select .= " GROUP BY MONTH(`".$table."`.`fecha` )";
				}

				
				return $qry_select;


}

	/**
	* @params: interval
	*
	*/
	
	function selectIntervalo( $interval, $table )
	{
		
		//La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
			switch( $interval ) 
			{
				case 'semana'	: $qry = "SELECT DAYOFWEEK(`".$table."`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYNAME(`".$table."`.`fecha`) AS `label` FROM `".$table."`  ";
						break;
				case 'mes'	: $qry = "SELECT DAYOFMONTH(`".$table."`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYOFMONTH(`".$table."`.`fecha`) AS `label` FROM `".$table."` ";
						break;
				case 'year'	: $qry = "SELECT MONTH(`".$table."`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, MONTHNAME(`".$table."`.`fecha`) AS `label` FROM `".$table."` ";
						break;
				default: break;
			}

		return $qry;
	
	}


	function selectComprasIntervalo( $interval )
	{
		
		//La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
			switch( $interval ) 
			{
				case 'semana'	: $qry = "SELECT DAYOFWEEK(`compras`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYNAME(`compras`.`fecha`) AS `label` FROM `compras`  ";
						break;
				case 'mes'	: $qry = "SELECT DAYOFMONTH(`compras`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYOFMONTH(`compras`.`fecha`) AS `label` FROM `compras` ";
						break;
				case 'year'	: $qry = "SELECT MONTH(`compras`.`fecha`) AS `x`, SUM(`subtotal`) AS `y`, MONTHNAME(`compras`.`fecha`) AS `label` FROM `compras` ";
						break;
				default: break;
			}

		return $qry;
	
	}

	/***************************************
	*	TERMINA UTILS
	****************************************/




	/*
	*	GRAFICA DE VENTAS EN GENERAL
	*
	*	params:
	*		'dateRange' puede ser ['semana', 'mes', year']
			opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'id_sucursal' : la sucursal de donde se sacaran los datos
	*/
	function graficaVentas(){

		
		//$array = getDateRangeGraphics('semana');
		$params = array();

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];
			$params = array();
			//Obtenemos el select del query para obtener las ventas
			$functionQuery = selectIntervalo($dateInterval, 'ventas');

			//getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
			$datesArray = getDateRangeGraphics($dateInterval);
			$qry_select = betweenDatesQueryPart($dateInterval, 'ventas');
			

			//Si se escogieron las fechas manualmente, se sobreescriben
			if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
			{
				$datesArray[0] = $_REQUEST['fecha-inicio'];
				$datesArray[1] = $_REQUEST['fecha-final'];
			}
			
			if ( isset( $_REQUEST['id_sucursal'] ) )
			{
				$functionQuery .= " WHERE `ventas`.`sucursal` = ? AND ";
				array_push( $params, $_REQUEST['id_sucursal'] );
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}
			else
			{
				$functionQuery .= " WHERE ";
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}

			//Formamos nuestro query completo
			$completeQuery = $functionQuery . $qry_select ;

			
			if ( $qry_select != false )
			{			
				//echo $qry_select;
				
				$listar = new listar( $completeQuery, $params);
				echo $listar->lista();

				return;
				
			}
			else
			{
				fail("Bad Request: dateRange");
				return;
			}

		}
		else
		{
			fail('Faltan parametros');
			return;
		}

	}
	

	/*
	*	GRAFICA DE VENTAS AL CONTADO 
	*
	*	params:
	*		'dateRange' puede ser ['semana', 'mes', year']
			opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

	*/
	function graficaVentasContado(){

		//$array = getDateRangeGraphics('semana');
		$params = array();

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];
			$params = array();
			//Obtenemos el select del query para obtener las ventas
			$functionQuery = selectIntervalo($dateInterval, 'ventas');

			//getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
			$datesArray = getDateRangeGraphics($dateInterval);
			$qry_select = betweenDatesQueryPart($dateInterval, 'ventas');

			

			//Si se escogieron las fechas manualmente, se sobreescriben
			if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
			{
				$datesArray[0] = $_REQUEST['fecha-inicio'];
				$datesArray[1] = $_REQUEST['fecha-final'];
			}
			
			//Si se mando el parametro id_sucursal lo agregamos a la clausula where
			if ( isset( $_REQUEST['id_sucursal'] ) )
			{
				$functionQuery .= " WHERE `ventas`.`sucursal` = ? AND ";
				array_push( $params, $_REQUEST['id_sucursal'] );
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}
			else
			{
				$functionQuery .= " WHERE ";
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}

			//Formamos nuestro query completo
			$completeQuery = $functionQuery . ' `ventas`.`tipo_venta` = 1 AND ' . $qry_select ;

			
			if ( $qry_select != false )
			{			
				//echo $qry_select;
				
				$listar = new listar( $completeQuery, $params);
				echo $listar->lista();

				return;
				
			}
			else
			{
				fail("Bad Request: dateRange");
				return;
			}

		}
		else
		{
			fail('Faltan parametros');
			return;
		}

	}


	/*
	*	GRAFICA DE VENTAS A CREDITO
	*
	*	params:
	*		'dateRange' puede ser ['semana', 'mes', year']
			opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

	*/
	function graficaVentasCredito(){

		//$array = getDateRangeGraphics('semana');
		$params = array();

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];
			$params = array();
			//Obtenemos el select del query para obtener las ventas
			$functionQuery = selectIntervalo($dateInterval, 'ventas');

			//getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
			$datesArray = getDateRangeGraphics($dateInterval);
			$qry_select = betweenDatesQueryPart($dateInterval, 'ventas');
			

			//Si se escogieron las fechas manualmente, se sobreescriben
			if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
			{
				$datesArray[0] = $_REQUEST['fecha-inicio'];
				$datesArray[1] = $_REQUEST['fecha-final'];
			}
			
			//Si se mando el parametro id_sucursal lo agregamos a la clausula where
			if ( isset( $_REQUEST['id_sucursal'] ) )
			{
				$functionQuery .= " WHERE `ventas`.`sucursal` = ? AND ";
				array_push( $params, $_REQUEST['id_sucursal'] );
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}
			else
			{
				$functionQuery .= " WHERE ";
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}

			//Formamos nuestro query completo
			$completeQuery = $functionQuery . ' `ventas`.`tipo_venta` = 2 AND ' . $qry_select ;

			
			if ( $qry_select != false )
			{			
				//echo $qry_select;
				
				$listar = new listar( $completeQuery, $params);
				echo $listar->lista();

				return;
				
			}
			else
			{
				fail("Bad Request: dateRange");
				return;
			}

		}
		else
		{
			fail('Faltan parametros');
			return;
		}

	}

	/*
	*	GRAFICA DE COMPRAS EN GENERAL
	*
	*	params:
	*		'dateRange' puede ser ['semana', 'mes', year']
			opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

	*/

	function graficaCompras()
	{

		$params = array();

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];
			$params = array();
			//Obtenemos el select del query para obtener las compras
			$functionQuery = selectIntervalo($dateInterval, 'compras');

			//getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
			$datesArray = getDateRangeGraphics($dateInterval);
			$qry_select = betweenDatesQueryPart($dateInterval, 'compras');
			

			//Si se escogieron las fechas manualmente, se sobreescriben
			if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
			{
				$datesArray[0] = $_REQUEST['fecha-inicio'];
				$datesArray[1] = $_REQUEST['fecha-final'];
			}
			
			if ( isset( $_REQUEST['id_sucursal'] ) )
			{
				$functionQuery .= " WHERE `compras`.`sucursal` = ? AND ";
				array_push( $params, $_REQUEST['id_sucursal'] );
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}
			else
			{
				$functionQuery .= " WHERE ";
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}

			//Formamos nuestro query completo
			$completeQuery = $functionQuery . $qry_select ;

			
			if ( $qry_select != false )
			{			
				//echo $qry_select;
				
				$listar = new listar( $completeQuery, $params);
				echo $listar->lista();

				return;
				
			}
			else
			{
				fail("Bad Request: dateRange");
				return;
			}

		}
		else
		{
			fail('Faltan parametros');
			return;
		}
		
		
	}


	/*
	*	GRAFICA DE COMPRAS AL CONTADO 
	*
	*	params:
	*		'dateRange' puede ser ['semana', 'mes', year']
			opcional 'fecha-inicio' y 'fecha-final' : tienen que ir juntos
			opcional 'id_sucursal' : la sucursal de donde se sacaran los datos

	*/
	function graficaComprasContado(){

		//$array = getDateRangeGraphics('semana');
		$params = array();

		if( isset( $_REQUEST['dateRange']) )
		{
			$dateInterval = $_REQUEST['dateRange'];
			$params = array();
			//Obtenemos el select del query para obtener las ventas
			$functionQuery = selectIntervalo($dateInterval, 'compras');

			//getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
			$datesArray = getDateRangeGraphics($dateInterval);
			$qry_select = betweenDatesQueryPart($dateInterval, 'compras');

			

			//Si se escogieron las fechas manualmente, se sobreescriben
			if ( isset( $_REQUEST['fecha-inicio']) && isset( $_REQUEST['fecha-final']) )
			{
				$datesArray[0] = $_REQUEST['fecha-inicio'];
				$datesArray[1] = $_REQUEST['fecha-final'];
			}
			
			//Si se mando el parametro id_sucursal lo agregamos a la clausula where
			if ( isset( $_REQUEST['id_sucursal'] ) )
			{
				$functionQuery .= " WHERE `compras`.`sucursal` = ? AND ";
				array_push( $params, $_REQUEST['id_sucursal'] );
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}
			else
			{
				$functionQuery .= " WHERE ";
				array_push( $params, $datesArray[0] );
				array_push( $params, $datesArray[1] );
			}

			//Formamos nuestro query completo
			$completeQuery = $functionQuery . ' `compras`.`tipo_compra` = 1 AND ' . $qry_select ;

			
			if ( $qry_select != false )
			{			
				//echo $qry_select;
				
				$listar = new listar( $completeQuery, $params);
				echo $listar->lista();

				return;
				
			}
			else
			{
				fail("Bad Request: dateRange");
				return;
			}

		}
		else
		{
			fail('Faltan parametros');
			return;
		}

	}


?>
