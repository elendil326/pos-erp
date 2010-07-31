<?php

require_once ('Estructura.php');
require_once("base/view_detalle_venta.dao.base.php");
require_once("base/view_detalle_venta.vo.base.php");
require_once("../server/misc/reportesUtils.php");
/** ViewDetalleVenta Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewDetalleVenta }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ViewDetalleVentaDAO extends ViewDetalleVentaDAOBase
{

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

	static function getAllVentas($timeRange, $fechaInicio, $fechaFinal, $id_sucursal=null, $tipo_venta = null, $id_producto = null)
	{
		
		//Si existen fechas usamos byRange si no getAll
		if ( $timeRange != null )
		{
			$datesArray = reportesUtils::getDateRange($timeRange);
			
			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$ventasFecha1 = new ViewDetalleVenta();
			$ventasFecha1->setFecha($datesArray[0]);

			$ventasFecha2 = new ViewDetalleVenta();
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

			if ( $id_producto != null )
			{
				$ventasFecha1->setIdProducto($id_producto);
				$ventasFecha2->setIdProducto($id_producto);
			}
			//$allVentas = ViewVentasDAO::getAll();
			$allVentas = ViewDetalleVentaDAO::byRange( $ventasFecha1, $ventasFecha2 );
			

		}
		else if ( $fechaInicio != null && $fechaFinal != null )
		{

			//Creamos los objetos ViewVentas para buscar en un rango de fechas
			$ventasFecha1 = new ViewDetalleVenta();
			$ventasFecha1->setFecha($fechaInicio);

			$ventasFecha2 = new ViewDetalleVenta();
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

			if ( $id_producto != null )
			{
				$ventasFecha1->setIdProducto($id_producto);
				$ventasFecha2->setIdProdcuto($id_producto);
			}

			//$allVentas = ViewVentasDAO::getAll();
			$allVentas = ViewDetalleVentaDAO::byRange( $ventasFecha1, $ventasFecha2 );
			
		}
		else
		{
			if ( $id_sucursal != null || $tipo_venta != null || $id_producto != null)
			{
				$viewVentas = new ViewDetalleVenta();
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
				
				$allVentas = ViewDetalleVentaDAO::search( $viewVentas );
			}
			else
			{
				$allVentas = ViewDetalleVentaDAO::getAll();
			}
		}	

		array_push( $allVentas, $ventasFecha1 );
		array_push( $allVentas, $ventasFecha2 );
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

	static function getProductoMasVendido( $timeRange, $fechaInicio, $fechaFinal )
	{
		
                $allVentas = ViewDetalleVentaDAO::getAllVentas( $timeRange, $fechaInicio, $fechaFinal );
		
		$ventasFecha2 = array_pop( $allVentas );
		$ventasFecha1 = array_pop( $allVentas );
		


		$arrayResults = array();//Arreglo con los pares de resultados, (id_usuario, total_vendido)
		$arrayID = array();//Guarda los id's que ya hemos analizado
		$duplicatedFlag = false; //Bandera que se activa si existe un id duplicado

		//Obtenemos un arreglo con pares de resultados, (id_usuario, total_vendido)
		//agrupamos cada usuario con la suma de lo que ha vendido
		for ( $i = 0; $i < count($allVentas) ; $i++ )
		{
			$id = $allVentas[$i]->getIdProducto();

			$duplicatedFlag = false;
			$productoVentas = null;
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
					$ventasFecha1->setIdProducto($id);
					$ventasFecha2->setIdProducto($id);
					$productoVentas = ViewDetalleVentaDAO::byRange( $ventasFecha1, $ventasFecha2 );
				}
				else
				{
					$viewVentas = new ViewDetalleVenta();
					$viewVentas->setIdProducto($id);
					$productoVentas = ViewDetalleVentaDAO::search( $viewVentas );
				}
				//echo "no duplicado";
				array_push($arrayID, $id);
			}

			//Teniendo ya las ventas de un unico usuario, sumamos el subtotal de lo 
			//que ha avendido y lo guardamos en nuestro arreglo de resultados junto
			//con el ID del usuario

			
			$sumaSubtotal = 0;
			if($productoVentas != null)
			{
				//var_dump($usuarioVentas);
				for ( $a = 0; $a < count($productoVentas) ; $a++ )
				{
					$sumaSubtotal += $productoVentas[$a]->getCantidad() * $productoVentas[$a]->getPrecio();
					
				}
				//echo $usuarioVentas[0]->getUsuario(). "-->" .$sumaSubtotal. "<br>";
				//break;
				array_push($arrayResults, array("producto" => $productoVentas[0]->getDenominacion(), "cantidad" => $sumaSubtotal) );

			}

			

		}

		if ( count($arrayResults) > 0 )
		{
			foreach ($arrayResults as $key => $row) {
			    $producto[$key]  = $row['producto'];
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

	static function getProductoMasVendidoSucursal( $id_sucursal, $timeRange, $fechaInicio, $fechaFinal)
	{	

		if ( $id_sucursal == null )
                {
                        return array( false, "Faltan parametros" );
                }

                $params = array($id_sucursal);

                        
                $qry_select = " SELECT `denominacion`, SUM(`cantidad`) AS `cantidad` FROM `view_detalle_venta` WHERE `id_sucursal` = ? ";


                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = reportesUtils::getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( $fechaInicio != null && $fechaFinal != null )
                {
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `cantidad` DESC LIMIT 1";

		return ViewDetalleVentaDAO::getResultArray( $qry_select, $params );

		


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


	static function getProductoIngresosTop( $timeRange, $fechaInicio, $fechaFinal )
	{

                $params = array();

                //Consulta para obtener el producto con el mayor numero de cantidad * precio        
		$qry_select = "SELECT `denominacion` AS `producto`, ROUND(SUM(`cantidad` * `precio`), 2) AS `ingresos` FROM `view_detalle_venta` ";

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = reportesUtils::getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " WHERE date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( $fechaInicio != null && $fechaFinal != null && $timeRange == null)
                {
                        array_push($params, $fechaInicio );
                        array_push($params, $fechaFinal );
                        $dateRange .= " WHERE date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `ingresos` DESC LIMIT 1";

                return ViewDetalleVentaDAO::getResultArray( $qry_select, $params );
		

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


	static function getDataVentasProducto($id_producto, $timeRange, $tipo_venta, $fechaInicio, $fechaFinal )
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                       $functionQuery = reportesUtils::selectDetallesIntervalo($dateInterval, 'view_detalle_venta');
			
                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_detalle_venta');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
			//Si se mando el id del producto se agrega a los parametros y se agrega la parte que corresponde a la consulta
			//sino se envia un error ya que este parametro es obligatorio
                        if ( $id_producto != null )
                        {
                                $functionQuery .= " WHERE `id_producto` = ? AND ";
                                array_push( $params, $id_producto );
				
				//Si se mando el tipo de venta se agrega a la consulta y a los parametros
				if ( $tipo_venta != null )
				{
					$functionQuery .= " `tipo_venta` = ? AND ";
					array_push( $params, $tipo_venta );
				}

                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
				return array( false, "Faltan parametros");
                        }

			
                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewDetalleVentaDAO::getResultArray( $completeQuery, $params);
                                
                        }
                        else
                        {
                                return array( false, "Bad Request: dateRange" );
                        }

                }
                else
                {
                        return array( false, "Faltan parametros" );
                }
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

	static function getDataProductosMasVendidos($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal)
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                       $functionQuery = "SELECT `id_producto` AS `x`, ROUND(SUM(`cantidad` * `precio`)) AS `y`, `denominacion` AS `label` FROM `view_detalle_venta`  WHERE ";
			
                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        //$qry_select = betweenDatesQueryPart($dateInterval, 'view_detalle_venta');
			$qry_select = " date(`fecha`) BETWEEN ? AND ? GROUP BY `id_producto` ORDER BY `y` DESC ";
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
			//Si se mando el id del producto se agrega a los parametros y se agrega la parte que corresponde a la consulta
			//sino se envia un error ya que este parametro es obligatorio
                        if ( $id_sucursal != null )
                        {
                                $functionQuery .= " `id_sucursal` = ? AND ";
                                array_push( $params, $id_sucursal );
				
                        }


			//Si se mando el tipo de venta se agrega a la consulta y a los parametros
			if ( $tipo_venta != null )
			{
				$functionQuery .= " `tipo_venta` = ? AND ";
				array_push( $params, $tipo_venta );
			}

			//Agregamos las dos fechas devueltas a los parametros de la consulta
			array_push( $params, $datesArray[0] );
			array_push( $params, $datesArray[1] );

			
                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewDetalleVentaDAO::getResultArray( $completeQuery, $params);
                                
                        }
                        else
                        {
                                return array( false, "Bad Request: dateRange" );
                        }

                }
                else
                {
                        return array( false, "Faltan parametros" );
                }
	}

}
