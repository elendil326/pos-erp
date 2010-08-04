<?php

require_once ('Estructura.php');
require_once("base/view_compras.dao.base.php");
require_once("base/view_compras.vo.base.php");
require_once("../server/misc/reportesUtils.php");

/** ViewCompras Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewCompras }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ViewComprasDAO extends ViewComprasDAOBase
{

	/**
        *       Obtiene un arreglo con los resultados de todas las compras, obtiene los
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

	static function getAllCompras($timeRange, $fechaInicio, $fechaFinal, $id_sucursal=null, $tipo_compra = null, $id_proveedor = null)
	{
		
		//Si existen fechas usamos byRange si no getAll
		if ( $timeRange != null )
		{
			$datesArray = reportesUtils::getDateRange($timeRange);
			
			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$comprasFecha1 = new ViewCompras();
			$comprasFecha1->setFecha($datesArray[0]);

			$comprasFecha2 = new ViewCompras();
			$comprasFecha2->setFecha($datesArray[1]);

			if ( $id_sucursal != null )
			{
				$comprasFecha1->setIdSucursal($id_sucursal);
				$comprasFecha2->setIdSucursal($id_sucursal);
			}
	
			if ( $tipo_compra != null )
			{
				$comprasFecha1->setTipoCompra($tipo_compra);
				$comprasFecha2->setTipoCompra($tipo_compra);
			}

			if ( $id_proveedor != null )
			{
				$comprasFecha1->setIdProveedor($id_proveedor);
				$comprasFecha2->setIdProveedor($id_proveedor);
			}
			//$allVentas = ViewVentasDAO::getAll();
			$allCompras = ViewComprasDAO::byRange( $comprasFecha1, $comprasFecha2 );
			

		}
		else if ( $fechaInicio != null && $fechaFinal != null )
		{

			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$comprasFecha1 = new ViewCompras();
			$comprasFecha1->setFecha($fechaInicio);

			$comprasFecha2 = new ViewCompras();
			$comprasFecha2->setFecha($fechaFinal);
	
			if ( $id_sucursal != null )
			{
				$comprasFechas1->setIdSucursal($id_sucursal);
				$comprasFechas2->setIdSucursal($id_sucursal);
			}
			
			if ( $tipo_venta != null )
			{
				$comprasFecha1->setTipoVenta($tipo_compra);
				$comprasFecha2->setTipoVenta($tipo_compra);
			}

			if ( $id_proveedor != null )
			{
				$comprasFecha1->setIdProveedor($id_proveedor);
				$comprasFecha2->setIdProveedor($id_proveedor);
			}

			//$allVentas = ViewVentasDAO::getAll();
			$allCompras = ViewComprasDAO::byRange( $comprasFecha1, $comprasFecha2 );
			
		}
		else
		{
			if ( $id_sucursal != null || $tipo_compra != null || $id_proveedor != null)
			{
				$viewCompras = new ViewCompras();
				if ( $id_sucursal != null)
				{
					$viewCompras->setIdSucursal($id_sucursal);
				}
				if ( $tipo_compra != null)
				{
					$viewCompras ->setTipoCompra($tipo_compra);
				}
				if ( $id_proveedor != null )
				{
					$viewCompras->setIdProveedor($id_proveedor);
				}
				
				$allCompras = ViewComprasDAO::search( $viewCompras );
			}
			else
			{
				$allCompras = ViewComprasDAO::getAll();
			}
		}	

		array_push( $allCompras, $comprasFecha1 );
		array_push( $allCompras, $comprasFecha2 );
		return $allCompras;


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


	static function getDataCompras($timeRange, $id_sucursal, $fechaInicio, $fechaFinal)
        {

                //Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allCompras = ViewComprasDAO::getAllCompras( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal );
		
		$comprasFecha2 = array_pop( $allCompras );
		$comprasFecha1 = array_pop( $allCompras );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allCompras) ; $i++ )
		{
			$fechaHora = $allCompras[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataCompras = null;
			$compraTupla = null;
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
				
				
				$compraTupla = array();			
				for($x=0; $x < count($allCompras); $x++)
				{
					$fullFecha = $allCompras[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $compraTupla, $allCompras[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($compraTupla != null)
			{
				
				for ( $a = 0; $a < count($compraTupla) ; $a++ )
				{
					$sumaSubtotal += $compraTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $compraTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

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
			$xylabelArray = ViewComprasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

                
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


	static function getDataComprasContado( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal ){

                //Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allCompras = ViewComprasDAO::getAllCompras( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal, 'contado' );
		
		$comprasFecha2 = array_pop( $allCompras );
		$comprasFecha1 = array_pop( $allCompras );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allCompras) ; $i++ )
		{
			$fechaHora = $allCompras[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataCompras = null;
			$compraTupla = null;
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
				
				
				$compraTupla = array();			
				for($x=0; $x < count($allCompras); $x++)
				{
					$fullFecha = $allCompras[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $compraTupla, $allCompras[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($compraTupla != null)
			{
				
				for ( $a = 0; $a < count($compraTupla) ; $a++ )
				{
					$sumaSubtotal += $compraTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $compraTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

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
			$xylabelArray = ViewComprasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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


	static function getDataComprasCredito( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal ){

                //Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allCompras = ViewComprasDAO::getAllCompras( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal, 'credito' );
		
		$comprasFecha2 = array_pop( $allCompras );
		$comprasFecha1 = array_pop( $allCompras );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allCompras) ; $i++ )
		{
			$fechaHora = $allCompras[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataCompras = null;
			$compraTupla = null;
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
				
				
				$compraTupla = array();			
				for($x=0; $x < count($allCompras); $x++)
				{
					$fullFecha = $allCompras[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $compraTupla, $allCompras[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($compraTupla != null)
			{
				
				for ( $a = 0; $a < count($compraTupla) ; $a++ )
				{
					$sumaSubtotal += $compraTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $compraTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

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
			$xylabelArray = ViewComprasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}

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

	
	static function getDataComprasProveedor($timeRange, $id_proveedor, $fechaInicio, $fechaFinal)
	{
		//Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allCompras = ViewComprasDAO::getAllCompras( $timeRange, $fechaInicio, $fechaFinal, null, null, $id_proveedor );
		
		$comprasFecha2 = array_pop( $allCompras );
		$comprasFecha1 = array_pop( $allCompras );

		$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allCompras) ; $i++ )
		{
			$fechaHora = $allCompras[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$dataCompras = null;
			$compraTupla = null;
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
				
				
				$compraTupla = array();			
				for($x=0; $x < count($allCompras); $x++)
				{
					$fullFecha = $allCompras[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $compraTupla, $allCompras[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($compraTupla != null)
			{
				
				for ( $a = 0; $a < count($compraTupla) ; $a++ )
				{
					$sumaSubtotal += $compraTupla[$a]->getSubtotal();
					
				}
				
				array_push($arrayResults, array("fecha" => $compraTupla[0]->getFecha(), "cantidad" => $sumaSubtotal) );
				

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
			$xylabelArray = ViewComprasDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}
	}


}
