<?php

require_once("base/view_detalle_venta.dao.base.php");
require_once("base/view_detalle_venta.vo.base.php");
require_once ('Estructura.php');
require_once("../misc/reportesUtils.php");
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

                return $array_result;


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
		
                $params = array();

                        
                //$qry_select = " SELECT `inventario`.`denominacion` AS `nombre`, SUM(`detalle_venta`.`cantidad`) AS `Cantidad` FROM `inventario`, `detalle_venta`, `ventas`  WHERE `inventario`.`id_producto` = `detalle_venta`.`id_producto` AND `detalle_venta`.`id_venta` = `ventas`.`id_venta`";
		$qry_select = "SELECT `denominacion` AS `nombre`, SUM(`cantidad`) AS `cantidad` FROM `view_detalle_venta`";

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

                $qry_select .= " GROUP BY `id_producto` ORDER BY `Cantidad` DESC LIMIT 1";

                return ViewDetalleVentaDAO::getResultArray( $qry_select, $params );


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

		if ( $id_sucursal != null )
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
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( $fechaInicio != null && $fechaFinal != null )
                {
                        array_push($params, $fechaInicio );
                        array_push($params, $fechaFinal );
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `ingresos` DESC LIMIT 1";

                return ViewDetalleVentaDAO::getResultArray( $qry_select, $params );
		

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


	static function getProductoGastoTop( $timeRange, $fechaInicio, $fechaFinal )
	{

                $params = array();

                //Consulta para obtener el producto con el mayor numero de cantidad * precio        
		$qry_select = "SELECT `denominacion` AS `producto`, ROUND(SUM(`cantidad` * `precio`), 2) AS `gastos` FROM `view_detalle_compra` ";

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " AND date(`fecha`) BETWEEN ? AND ?";
                        }

                        
                }

                if ( $fechaInicio != null && $fechaFinal != null )
                {
                        array_push($params, $fechaInicio );
                        array_push($params, $fechaFinal );
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_producto` ORDER BY `gastos` DESC LIMIT 1";

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
                                echo "{ success: false, error: 'Faltan parametros: Producto'}";
				return;
                        }

			
                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewDetalleVentasDAO::getResultArray( $completeQuery, $params);
                                
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

	static function getDataProductosMasVendidos($conn)
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
			$qry_select = " date(`fecha`) BETWEEN ? AND ? GROUP BY `id_producto` DESC";
                        

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
				return ViewDetalleVentasDAO::getResultArray( $completeQuery, $params);
                                
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
