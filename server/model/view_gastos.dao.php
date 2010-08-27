<?php

require_once ('Estructura.php');
require_once("base/view_gastos.dao.base.php");
require_once("base/view_gastos.vo.base.php");
require_once("../server/misc/reportesUtils.php");
/** ViewGastos Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewGastos }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ViewGastosDAO extends ViewGastosDAOBase
{


	static function groupDataByDate( &$allVentas, $timeRange )
	{
		
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
				if ( $timeRange == "year" )
				{
					if ( $compYear == $compYear2 && $compMonth == $compMonth2)
					{
						
						$duplicatedFlag = true;
						
					}
				}
				if ( $timeRange == "mes" || $timeRange == "semana" )
				{
					if ( $compYear == $compYear2 && $compMonth == $compMonth2 && $compDay == $compDay2)
					{
						
						$duplicatedFlag = true;
						
					}
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
					
					if ( $timeRange == "year" )
					{
						if ( $y == $y2 && $m == $m2 )
						{
					
							array_push( $ventaTupla, $allVentas[$x]);
						}
					}
					
					if ( $timeRange == "mes" || $timeRange == "semana" )
					{
						if ( $d == $d2 && $m == $m2 && $y == $y2 )
						{

							array_push( $ventaTupla, $allVentas[$x]);
						}
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
					$sumaSubtotal += $ventaTupla[$a]->getMonto();
					
				}
				
				array_push($arrayResults, array("fecha" => $ventaTupla[0]->getFecha(), "monto" => $sumaSubtotal) );
				

			}

			

		}
	
		return $arrayResults;
	}


	/**
        *       Obtiene un arreglo con los resultados de todas las gastos, obtiene los
	*	resultados dependiendo del rango de tiempo que se pida
        *
        *       @author Luis Michel <luismichel@computer.org>
	*	@static
	*	@param String Cadena con el tipo de rango (semana, mes, year)
	*	@param String Fecha de inicio del rango de tiempo, se utiliza si se quiere ingresar el rango manualmente
	*	@param String Fecha final del rango de tiempo, se utiliza si se quiere ingresar el rango manualmente
	*	@param Integer ID de la sucursal de donde se quieren obtener los datos
        *       @return Array un arreglo con los datos obtenidos de las gastos
        */	

	static function getAllGastos($timeRange, $fechaInicio, $fechaFinal, $id_sucursal=null)
	{
		
		//Si existen fechas usamos byRange si no getAll
		if ( $timeRange != null )
		{
			$datesArray = reportesUtils::getDateRange($timeRange);
			
			//Creamos los objetos ViewGastos para buscar en un rango de fechas
			$gastosFecha1 = new ViewGastos();
			$gastosFecha1->setFecha($datesArray[0]);

			$gastosFecha2 = new ViewGastos();
			$gastosFecha2->setFecha($datesArray[1]);

			if ( $id_sucursal != null )
			{
				$gastosFecha1->setIdSucursal($id_sucursal);
				$gastosFecha2->setIdSucursal($id_sucursal);
			}
	

			//$allGastos = ViewGastosDAO::getAll();
			$allGastos = ViewGastosDAO::byRange( $gastosFecha1, $gastosFecha2 );
			

		}
		else if ( $fechaInicio != null && $fechaFinal != null )
		{

			//Creamos los objetos ViewGastos para buscar en un rango de fechas
			$gastosFecha1 = new ViewGastos();
			$gastosFecha1->setFecha($fechaInicio);

			$gastosFecha2 = new ViewGastos();
			$gastosFecha2->setFecha($fechaFinal);
	
			if ( $id_sucursal != null )
			{
				$gastosFechas1->setIdSucursal($id_sucursal);
				$gastosFechas2->setIdSucursal($id_sucursal);
			}
			
			//$allGastos = ViewGastosDAO::getAll();
			$allGastos = ViewGastosDAO::byRange( $gastosFecha1, $gastosFecha2 );
			
		}
		else
		{
			if ( $id_sucursal != null)
			{
				$viewGastos = new ViewGastos();
				if ( $id_sucursal != null)
				{
					$viewGastos->setIdSucursal($id_sucursal);
				}
				
				$allGastos = ViewGastosDAO::search( $viewGastos );
			}
			else
			{
				$allGastos = ViewGastosDAO::getAll();
			}
		}	

		array_push( $allGastos, $gastosFecha1 );
		array_push( $allGastos, $gastosFecha2 );
		return $allGastos;


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
						$monto[$key] = $row['monto'];
						
						$fechaSinHoras = explode(' ',$fecha[$key]);
						list($year,$month,$day) = explode('-',$fechaSinHoras[0]);
			
						$formatDate = date("l", mktime(0, 0, 0, $month, $day, $year));
						//echo "dia: ".$day." mes: ".$month." year: ".$year."<br>";
						array_push( $resultArray, array("x" => $fechaSinHoras[0], "y" => $monto[$key], "label" => $formatDate));

					}
					break;


			case "mes" :
					
					foreach ($data as $key => $row) {
						$fecha[$key]  = $row['fecha'];
						$monto[$key] = $row['monto'];
						
						$fechaSinHoras = explode(' ',$fecha[$key]);
						list($year,$month,$day) = explode('-',$fechaSinHoras[0]);
			
						$formatDate = date("l", mktime(0, 0, 0, $month, $day, $year));
						//echo "dia: ".$day." mes: ".$month." year: ".$year."<br>";
						array_push( $resultArray, array("x" => $fechaSinHoras[0], "y" => $monto[$key], "label" => $day));

					}
					break;

			case 'year' : 

					$arrayMonths = array(); //Guarda los meses que ya hemos analizado
					foreach ($data as $key => $row) {
						$fecha[$key]  = $row['fecha'];
						$monto[$key] = $row['monto'];
						$sumamonto = 0;
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
								$monto2[$key] = $renglon['monto'];
						
								$fechaSinHoras2 = explode(' ',$fecha2[$key]);
								list($year2,$month2,$day2) = explode('-',$fechaSinHoras2[0]);
								//var_dump($lastMonth); break;
								if ( $lastMonth[0] == $month2 && $lastMonth[1] == $year2 )
								{
									$sumamonto += $monto2[$key];
								}

							}
							//echo "year : ".$year."<br>";
							array_push( $arrayMonths, array( "mesX" => $month, "yearX" => $year ));
							//echo count($arrayMonths)."<br>";
						}
			
			
						$formatDate = date("M", mktime(0, 0, 0, $month, $day, $year));
						//echo "dia: ".$day." mes: ".$month." year: ".$year."<br>";
						array_push( $resultArray, array("x" => $fechaSinHoras[0], "y" => $sumamonto, "label" => $formatDate));

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
        *       Obtiene los datos para generar una grafica de los gastos en general.
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


	static function getDataGastos( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
        {

                //Si no se manda un timeRange termina la funcion
		if ( $timeRange == null && $fechaInicio == null && $fechaFinal == null )
		{
			return array("Faltan parametros");
		}


		$allgastos = ViewGastosDAO::getAllGastos( $timeRange, $fechaInicio, $fechaFinal, $id_sucursal );
		
		$gastosFecha2 = array_pop( $allgastos );
		$gastosFecha1 = array_pop( $allgastos );

		$arrayResults = ViewGastosDAO::groupDataByDate( $allgastos, $timeRange );
		
		/*$arrayResults = array();//Arreglo con los pares de resultados, (id_sucursal, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_sucursal, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allgastos) ; $i++ )
		{
			$fechaHora = $allgastos[$i]->getFecha();
			
			$fechaYMD = explode(" ", $fechaHora);
			$fecha = $fechaYMD[0];
			list($compYear, $compMonth, $compDay) = explode("-", $fecha); //obtenemos año, mes, dia para comparar
			$duplicatedFlag = false;
			$datagastos = null;
			$gastoTupla = null;
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
				
				
				$gastoTupla = array();			
				for($x=0; $x < count($allgastos); $x++)
				{
					$fullFecha = $allgastos[$x]->getFecha();

					$fechaArray = explode(" ", $fullFecha);
					$fechaSinHoras = $fechaArray[0];
				
				
					list($y, $m, $d) = explode("-", $fechaSinHoras);
					list($y2, $m2, $d2 ) = explode( "-", $fecha );

					if ( $y == $y2 && $m == $m2 )
					{
						
						array_push( $gastoTupla, $allgastos[$x]);
					}	
				}
				array_push($arrayID, $fecha);
			}

			//Teniendo ya las gastos de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			
			if($gastoTupla != null)
			{
				
				for ( $a = 0; $a < count($gastoTupla) ; $a++ )
				{
					$sumaSubtotal += $gastoTupla[$a]->getMonto();
					
				}
				
				array_push($arrayResults, array("fecha" => $gastoTupla[0]->getFecha(), "monto" => $sumaSubtotal) );
				

			}

			

		}*/

		
		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $fecha[$key]  = $row['fecha'];
			    $monto[$key] = $row['monto'];
			}

			array_multisort($fecha, SORT_ASC, $arrayResults);

			//return $arrayResults;
			$xylabelArray = ViewGastosDAO::formatData( $arrayResults, $timeRange);
			return $xylabelArray;
		}
		else
		{
			return array( "No se encontraron datos" );
		}               
                
        }



}
