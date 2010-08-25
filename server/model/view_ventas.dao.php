<?php

require_once ('Estructura.php');
require_once("base/view_ventas.dao.base.php");
require_once("base/view_ventas.vo.base.php");
require_once("../server/misc/reportesUtils.php");
/** ViewVentas Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewVentas }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ViewVentasDAO extends ViewVentasDAOBase
{

	/**
        *       Obtiene un arreglo con los resultados de todas las ventas, obtiene los
	*	resultados dependiendo del rango de tiempo que se pida
        *
        *       @author Luis Michel <luismichel@computer.org>
	*	@static
	*	@param String Cadena con el tipo de rango (semana, mes, year)
	*	@param String Fecha de inicio del rango de tiempo, se utiliza si se quiere ingresar el rango manualmente
	*	@param String Fecha final del rango de tiempo, se utiliza si se quiere ingresar el rango manualmente
	*	@param Integer ID de la sucursal de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de las ventas
        */	

	static function getAllVentas($timeRange, $fechaInicio, $fechaFinal, $id_sucursal=null, $tipo_venta = null, $id_cliente = null)
	{
		
		//Si existen fechas usamos byRange si no getAll
		if ( $timeRange != null )
		{
			$datesArray = reportesUtils::getDateRange($timeRange);
			var_dump($datesArray);
			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$ventasFecha1 = new ViewVentas();
			$ventasFecha1->setFecha($datesArray[0]);

			$ventasFecha2 = new ViewVentas();
			$ventasFecha2->setFecha($datesArray[1]);

			if ( $id_sucursal != null )
			{
				$ventasFecha1->setIdSucursal($id_sucursal);
				$ventasFecha2->setIdSucursal($id_sucursal);
			}
	
			if ( $tipo_venta != null )
			{
				$ventasFecha1->setTipoVenta($tipo_venta);
				$ventasFecha2->setTipoVenta($tipo_venta);
			}

			if ( $id_cliente != null )
			{
				$ventasFecha1->setIdCliente($id_cliente);
				$ventasFecha2->setIdCliente($id_cliente);
			}
			//$allVentas = ViewVentasDAO::getAll();
			$allVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
			array_push( $allVentas, $ventasFecha1 );
			array_push( $allVentas, $ventasFecha2 );

		}
		else if ( $fechaInicio != null && $fechaFinal != null )
		{

			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$ventasFecha1 = new ViewVentas();
			$ventasFecha1->setFecha($fechaInicio);

			$ventasFecha2 = new ViewVentas();
			$ventasFecha2->setFecha($fechaFinal);
	
			if ( $id_sucursal != null )
			{
				$ventasFechas1->setIdSucursal($id_sucursal);
				$ventasFechas2->setIdSucursal($id_sucursal);
			}
			
			if ( $tipo_venta != null )
			{
				$ventasFecha1->setTipoVenta($tipo_venta);
				$ventasFecha2->setTipoVenta($tipo_venta);
			}

			if ( $id_cliente != null )
			{
				$ventasFecha1->setIdCliente($id_cliente);
				$ventasFecha2->setIdCliente($id_cliente);
			}

			//$allVentas = ViewVentasDAO::getAll();
			$allVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
			array_push( $allVentas, $ventasFecha1 );
			array_push( $allVentas, $ventasFecha2 );
		}
		else
		{
			if ( $id_sucursal != null || $tipo_venta != null || $id_cliente != null)
			{
				$viewVentas = new ViewVentas();
				if ( $id_sucursal != null)
				{
					$viewVentas->setIdSucursal($id_sucursal);
				}
				if ( $tipo_venta != null)
				{
					$viewVentas->setTipoVenta($tipo_venta);
				}
				if ( $id_cliente != null )
				{
					$viewVentas->setIdCliente($id_cliente);
				}
				
				$allVentas = ViewVentasDAO::search( $viewVentas );
			}
			else
			{
				$allVentas = ViewVentasDAO::getAll();
			}
		}	

		//array_push( $allVentas, $ventasFecha1 );
		//array_push( $allVentas, $ventasFecha2 );
		return $allVentas;


	}


	/**
        *       Obtiene un arreglo con los resultados en el formato que se solicita
        *
        *       @author Luis Michel <luismichel@computer.org>
	*	@static
	*	@param String Una cadena con el tipo de formato de los datos (semana, mes, year )
	*	@param Array Un arreglo que contiene los resultados sin formato
        *       @return Array un arreglo que contiene los resultados con el formato especificado
        */	


	static function formatData( $data, $timeFormat )
	{

		$resultArray = array();

		//Obtenemos nuestro arreglo con x, y y label, y con las fechas formateadas
		switch($timeFormat)
		{
			case "semana" : 

					/*for($i = 0; $i < count($data) ; $i++)
					{
						//Separamos año, mes y dia de la fecha que nos enviaron
						list($year,$month,$day) = split('-',$data[$i]);
						
					}*/
					foreach ($data as $key => $row) {
						$fecha[$key]  = $row['fecha'];
						$cantidad[$key] = $row['cantidad'];
						
						$fechaSinHoras = explode(' ',$fecha[$key]);
						list($year,$month,$day) = explode('-',$fechaSinHoras[0]);
			
						$formatDate = date("l", mktime(0, 0, 0, $month, $day, $year));
						//echo "dia: ".$day." mes: ".$month." year: ".$year."<br>";
						array_push( $resultArray, array("x" => $fechaSinHoras[0], "y" => $cantidad[$key], "label" => $formatDate));

					}
					break;


			case "mes" :
					
					foreach ($data as $key => $row) {
						$fecha[$key]  = $row['fecha'];
						$cantidad[$key] = $row['cantidad'];
						
						$fechaSinHoras = explode(' ',$fecha[$key]);
						list($year,$month,$day) = explode('-',$fechaSinHoras[0]);
			
						$formatDate = date("l", mktime(0, 0, 0, $month, $day, $year));
						//echo "dia: ".$day." mes: ".$month." year: ".$year."<br>";
						array_push( $resultArray, array("x" => $fechaSinHoras[0], "y" => $cantidad[$key], "label" => $day));

					}
					break;

			case 'year' : 

					$arrayMonths = array(); //Guarda los meses que ya hemos analizado
					foreach ($data as $key => $row) {
						$fecha[$key]  = $row['fecha'];
						$cantidad[$key] = $row['cantidad'];
						$sumaCantidad = 0;
						$duplicatedFlag = false;
						$year = null; $month=null; $day = null;

						$lastMonth = array(); //Guarda el mes y el año, para comparar entre meses iguales
						
						$fechaSinHoras = explode(' ',$fecha[$key]);
						list($year,$month,$day) = explode('-',$fechaSinHoras[0]);

						array_push($lastMonth, $month);
						array_push($lastMonth, $year);
						//Agrupamos las tuplas por mes
		
						
						foreach( $arrayMonths as $key2 => $x){
							$year2 = null;
							$mes2[$key2] = $x['mesX'];
							$year2[$key2] = $x["yearX"];


							//echo "mes: ".$mes2[$key2]." year: ".$year2[$key2]."<br>";

							if( $mes2[$key2] == $lastMonth[0] && $year2[$key2] == $lastMonth[1])
							{
								//echo "mes: ".$mes2[$key2]." year: ".$year2[$key2]."<br>";
								//echo "mes: ".$lastMonth[0]." year: ".$lastMonth[1]."<br>";	
								$duplicatedFlag = true;
							}
							
						}



						if( $duplicatedFlag != true)
						{
							foreach ($data as $key => $renglon) {
								$fecha2[$key]  = $renglon['fecha'];
								$cantidad2[$key] = $renglon['cantidad'];
						
								$fechaSinHoras2 = explode(' ',$fecha2[$key]);
								list($year2,$month2,$day2) = explode('-',$fechaSinHoras2[0]);
								//var_dump($lastMonth); break;
								if ( $lastMonth[0] == $month2 && $lastMonth[1] == $year2 )
								{
									$sumaCantidad += $cantidad2[$key];
								}

							}
							//echo "year : ".$year."<br>";
							array_push( $arrayMonths, array( "mesX" => $month, "yearX" => $year ));
							//echo count($arrayMonths)."<br>";
						}
			
			
						$formatDate = date("M", mktime(0, 0, 0, $month, $day, $year));
						//echo "dia: ".$day." mes: ".$month." year: ".$year."<br>";
						array_push( $resultArray, array("x" => $fechaSinHoras[0], "y" => $sumaCantidad, "label" => $formatDate));

					}
					
					break;

					
		}
		//var_dump($resultArray);
		return $resultArray;

	}



	/**
        *       Obtiene un arreglo con los resultados de la consulta
        *
        *       @author Luis Michel <luismichel@computer.org>
	*	@static
	*	@param String Una cadena con la consulta que se quiere ejecutar
	*	@param Array Un arreglo que contiene los parametros de la consulta
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */	


	static function getResultArray( $sqlQuery, $params )
	{
		global $conn;

		//Probamos ejecutar la consulta
		try{	
	    	$result = $conn->Execute($sqlQuery, $params);
		}catch(Exception $e){
		  $logger->log($e->getMessage(), PEAR_LOG_ERR);
		}

		//Formamos nuestro arreglo con los resultados
		$array_result = array();
		while($objResult = $result->fetchNextObject(false)) {
		    array_push($array_result, $objResult);
		}


		if ( count($array_result) < 1 )
		{
			return array("No se encontraron datos");
		}
		else
		{
                	return $array_result;
		}


	}

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
        *       @return Array un arreglo con los datos obtenidos de la consulta
        */

	static function getVendedorMasProductivo( $timeRange, $fechaInicio, $fechaFinal )
	{

		$allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		//Si existen fechas usamos byRange si no getAll
		/*if ( $timeRange != null )
		{
			$datesArray = reportesUtils::getDateRange($timeRange);

			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$ventasFecha1 = new ViewVentas();
			$ventasFecha1->setFecha($datesArray[0]);

			$ventasFecha2 = new ViewVentas();
			$ventasFecha2->setFecha($datesArray[1]);
	
			//$allVentas = ViewVentasDAO::getAll();
			$allVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
			

		}
		else if ( $fechaInicio != null && $fechaFinal != null )
		{

			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$ventasFecha1 = new ViewVentas();
			$ventasFecha1->setFecha($fechaInicio);

			$ventasFecha2 = new ViewVentas();
			$ventasFecha2->setFecha($fechaFinal);
	
			//$allVentas = ViewVentasDAO::getAll();
			$allVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
			
		}
		else
		{
			$allVentas = ViewVentasDAO::getAll();
		}*/	


		$arrayResults = array();//Arreglo con los pares de resultados, (id_usuario, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_usuario, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdUsuario();

			$duplicatedFlag = false;
			$usuarioVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdUsuario($id);
					$ventasFecha2->setIdUsuario($id);
					$usuarioVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$viewVentas = new ViewVentas();
					$viewVentas->setIdUsuario($id);
					$usuarioVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($usuarioVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($usuarioVentas) ; $a++ )
				{
					$sumaSubtotal += $usuarioVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("usuario" => $usuarioVentas[0]->getUsuario(), "cantidad" => $sumaSubtotal, "sucursal" => $usuarioVentas[0]->getSucursal()) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $usuario[$key]  = $row['usuario'];
			    $cantidad[$key] = $row['cantidad'];
			}

			array_multisort($cantidad, SORT_DESC, $arrayResults);

			return $arrayResults;
		}
		else
		{
			return array( "No se encontraron datos" );
		}
                

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
	
	static function getSucursalVentasTop( $timeRange, $fechaInicio, $fechaFinal )
	{

		$allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdSucursal();

			$duplicatedFlag = false;
			$sucursalVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				$viewVentas = new ViewVentas();
				$viewVentas->setIdSucursal($id);
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdSucursal($id);
					$ventasFecha2->setIdSucursal($id);
					$sucursalVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$sucursalVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($sucursalVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($sucursalVentas) ; $a++ )
				{
					$sumaSubtotal += $sucursalVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("sucursal" => $sucursalVentas[0]->getSucursal(), "cantidad" => $sumaSubtotal) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $sucursal[$key]  = $row['sucursal'];
			    $cantidad[$key] = $row['cantidad'];
			}

			array_multisort($cantidad, SORT_DESC, $arrayResults);

			return $arrayResults;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	static function getClienteComprasTop( $timeRange, $fechaInicio, $fechaFinal )
	{

                $allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdCliente();

			$duplicatedFlag = false;
			$clienteVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				$viewVentas = new ViewVentas();
				$viewVentas->setIdCliente($id);
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdCliente($id);
					$ventasFecha2->setIdCliente($id);
					$clienteVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$clienteVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($clienteVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($clienteVentas) ; $a++ )
				{
					$sumaSubtotal += $clienteVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("cliente" => $clienteVentas[0]->getCliente(), "cantidad" => $sumaSubtotal) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $cliente[$key]  = $row['cliente'];
			    $cantidad[$key] = $row['cantidad'];
			}

			array_multisort($cantidad, SORT_DESC, $arrayResults);

			return $arrayResults;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	static function getVendedorMasProductivoSucursal( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal, $formatoGrafica )
	{

		if ( $id_sucursal == null )
                {
                        return array( false, "Faltan parametros" );
                }
                
            
        $allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		

		$arrayResults = array();//Arreglo con los pares de resultados, (id_usuario, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_usuario, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdUsuario();

			$duplicatedFlag = false;
			$usuarioVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdUsuario($id);
					$ventasFecha2->setIdUsuario($id);
					$usuarioVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$viewVentas = new ViewVentas();
					$viewVentas->setIdUsuario($id);
					$usuarioVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($usuarioVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($usuarioVentas) ; $a++ )
				{
					$sumaSubtotal += $usuarioVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				if( $formatoGrafica == true  )
				{
					array_push($arrayResults, array("x" => $usuarioVentas[0]->getUsuario(), "y" => $sumaSubtotal, "label" => $usuarioVentas[0]->getUsuario() ));
				}
				else
				{
					array_push($arrayResults, array("usuario" => $usuarioVentas[0]->getUsuario(), "cantidad" => $sumaSubtotal) );
				}

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			if( $formatoGrafica == true )
			{
			
				foreach ($arrayResults as $key => $row) {
					$x[$key]  = $row['x'];
					$y[$key] = $row['y'];
					$label[$key] = $row['label'];
				}
			
				array_multisort($y, SORT_DESC, $arrayResults);
				
			}
			else
			{
				foreach ($arrayResults as $key => $row) {
					$usuario[$key]  = $row['usuario'];
					$cantidad[$key] = $row['cantidad'];
				}
			
				array_multisort($cantidad, SORT_DESC, $arrayResults);	
			}
			
			return $arrayResults;
			
		}
		else
		{
			return array( "No se encontraron datos" );
		}
	
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


	static function getClienteComprasTopSucursal( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal)
	{

		if ( $id_sucursal == null )
                {
			return array( false, "Faltan parametros");
                }
                
                $allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdCliente();

			$duplicatedFlag = false;
			$clienteVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				$viewVentas = new ViewVentas();
				$viewVentas->setIdCliente($id);
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdCliente($id);
					$ventasFecha2->setIdCliente($id);
					$clienteVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$clienteVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($clienteVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($clienteVentas) ; $a++ )
				{
					$sumaSubtotal += $clienteVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("cliente" => $clienteVentas[0]->getCliente(), "cantidad" => $sumaSubtotal) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $cliente[$key]  = $row['cliente'];
			    $cantidad[$key] = $row['cantidad'];
			}

			array_multisort($cantidad, SORT_DESC, $arrayResults);

			return $arrayResults;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	static function getClienteComprasCreditoTop( $timeRange, $fechaInicio, $fechaFinal )
	{

                $allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		
		
		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdCliente();
			
			$duplicatedFlag = false;
			$clienteVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdCliente($id);
					$ventasFecha1->setTipoVenta('credito');
					$ventasFecha2->setIdCliente($id);
					$ventasFecha2->setTipoVenta('credito');
					$clienteVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$viewVentas = new ViewVentas();
					$viewVentas->setIdCliente($id);
					$viewVentas->setTipoVenta('credito');
					$clienteVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($clienteVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($clienteVentas) ; $a++ )
				{
					$sumaSubtotal += $clienteVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("cliente" => $clienteVentas[0]->getCliente(), "cantidad" => $sumaSubtotal) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $cliente[$key]  = $row['cliente'];
			    $cantidad[$key] = $row['cantidad'];
			}

			array_multisort($cantidad, SORT_DESC, $arrayResults);

			return $arrayResults;
		}
		else
		{
			return array( "No se encontraron datos" );
		}



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

	static function getClienteComprasContadoTop( $timeRange, $fechaInicio, $fechaFinal )
	{


                $allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdCliente();

			$duplicatedFlag = false;
			$clienteVentas = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				if ( $id == $arrayID[$j])
				{
					$duplicatedFlag = true;
					//echo "no duplicado";
				}
			}

			if( $duplicatedFlag != true)
			{
				
				if ( $timeRange != null || $fechaInicio != null || $fechaFinal != null )
				{
					$ventasFecha1->setIdCliente($id);
					$ventasFecha1->setTipoVenta('contado');
					$ventasFecha2->setIdCliente($id);
					$ventasFecha2->setTipoVenta('contado');
					$clienteVentas = ViewVentasDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$viewVentas = new ViewVentas();
					$viewVentas->setIdCliente($id);
					$viewVentas->setTipoVenta('contado');
					$clienteVentas = ViewVentasDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($clienteVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($clienteVentas) ; $a++ )
				{
					$sumaSubtotal += $clienteVentas[$a]->getSubtotal();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("cliente" => $clienteVentas[0]->getCliente(), "cantidad" => $sumaSubtotal) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $cliente[$key]  = $row['cliente'];
			    $cantidad[$key] = $row['cantidad'];
			}

			array_multisort($cantidad, SORT_DESC, $arrayResults);

			return $arrayResults;
		}
		else
		{
			return array( "No se encontraron datos" );
		}



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

	static function getDataVentas( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal ){


		//Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$fechaHora = $allVentas[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataVentas = null;
			$ventaTupla = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				list($compYear2, $compMonth2, $compDay2) = explode("-", $arrayID[$j]);
				if ( $compYear == $compYear2 && $compMonth == $compMonth2)
				{
					
					$duplicatedFlag = true;
					
				}
			}

			if( $duplicatedFlag != true)
			{
				
				
				$ventaTupla = array();			
				for($x=0; $x < count($allVentas); $x++)
				{
					$fullFecha = $allVentas[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $ventaTupla, $allVentas[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($ventaTupla != null)
			{
				
				for ( $a = 0; $a < count($ventaTupla) ; $a++ )
				{
					$sumaSubtotal += $ventaTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $ventaTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

			}

			

		}

		
		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $fecha[$key]  = $row['fecha'];
			    $cantidad[$key] = $row['cantidad'];
			}

			//array_multisort($fecha, SORT_DESC, $arrayResults);

			//return $arrayResults;
			$xylabelArray = ViewVentasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	
	static function getDataVentasContado( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal ){

                //Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal, "contado" );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$fechaHora = $allVentas[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataVentas = null;
			$ventaTupla = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				list($compYear2, $compMonth2, $compDay2) = explode("-", $arrayID[$j]);
				if ( $compYear == $compYear2 && $compMonth == $compMonth2)
				{
					
					$duplicatedFlag = true;
					
				}
			}

			if( $duplicatedFlag != true)
			{
				
				
				$ventaTupla = array();			
				for($x=0; $x < count($allVentas); $x++)
				{
					$fullFecha = $allVentas[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $ventaTupla, $allVentas[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($ventaTupla != null)
			{
				
				for ( $a = 0; $a < count($ventaTupla) ; $a++ )
				{
					$sumaSubtotal += $ventaTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $ventaTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

			}

			

		}

		
		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $fecha[$key]  = $row['fecha'];
			    $cantidad[$key] = $row['cantidad'];
			}

			//array_multisort($fecha, SORT_DESC, $arrayResults);

			//return $arrayResults;
			$xylabelArray = ViewVentasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	static function getDataVentasCredito($timeRange, $id_sucursal, $fechaInicio, $fechaFinal){

                //Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal, "credito" );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$fechaHora = $allVentas[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataVentas = null;
			$ventaTupla = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				list($compYear2, $compMonth2, $compDay2) = explode("-", $arrayID[$j]);
				if ( $compYear == $compYear2 && $compMonth == $compMonth2)
				{
					
					$duplicatedFlag = true;
					
				}
			}

			if( $duplicatedFlag != true)
			{
				
				
				$ventaTupla = array();			
				for($x=0; $x < count($allVentas); $x++)
				{
					$fullFecha = $allVentas[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $ventaTupla, $allVentas[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($ventaTupla != null)
			{
				
				for ( $a = 0; $a < count($ventaTupla) ; $a++ )
				{
					$sumaSubtotal += $ventaTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $ventaTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

			}

			

		}

		
		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $fecha[$key]  = $row['fecha'];
			    $cantidad[$key] = $row['cantidad'];
			}

			//array_multisort($fecha, SORT_DESC, $arrayResults);

			//return $arrayResults;
			$xylabelArray = ViewVentasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	static function getDataVentasCliente($timeRange, $id_cliente, $fechaInicio, $fechaFinal)
	{
		if ( $id_cliente == null )
		{
			return array("Faltan parametros");
		}

		//Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}
		

		$allVentas = ViewVentasDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal, null, null, $id_cliente );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		
		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$fechaHora = $allVentas[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataVentas = null;
			$ventaTupla = null;
			//Buscamos en nuestro arreglo de ID's que ya hemos analizado
			//para que no se repitan datos, si el currentId no se ha analizado
			//se hace una busqueda de ese ID y se guardan todas las tuplas
			for ( $j = 0; $j < count($arrayID) ; $j++)
			{
				//echo $id ."---->". $arrayID[$j]."<br>";
				list($compYear2, $compMonth2, $compDay2) = explode("-", $arrayID[$j]);
				if ( $compYear == $compYear2 && $compMonth == $compMonth2)
				{
					
					$duplicatedFlag = true;
					
				}
			}

			if( $duplicatedFlag != true)
			{
				
				
				$ventaTupla = array();			
				for($x=0; $x < count($allVentas); $x++)
				{
					$fullFecha = $allVentas[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $ventaTupla, $allVentas[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($ventaTupla != null)
			{
				
				for ( $a = 0; $a < count($ventaTupla) ; $a++ )
				{
					$sumaSubtotal += $ventaTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $ventaTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

			}

			

		}

		
		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $fecha[$key]  = $row['fecha'];
			    $cantidad[$key] = $row['cantidad'];
			}

			//array_multisort($fecha, SORT_DESC, $arrayResults);

			//return $arrayResults;
			$xylabelArray = ViewVentasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}	

	}


}
