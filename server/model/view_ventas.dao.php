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
		global $conn;
		
                $params = array();

                //$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` ";                         
		$qry_select = "SELECT `usuario`, SUM(`subtotal`) AS `vendido` FROM `view_ventas`";


                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;


                        //Escogemos el rango de tiempo para los datos (Semana, Mes, Año, Todos)        
                        $datesArray = reportesUtils::getDateRange($dateInterval);      

                        if( $datesArray != false )
                        {
                                
                                array_push($params, $datesArray[0]);
                                array_push($params, $datesArray[1]);

                                $qry_select .= " WHERE date(`view_ventas`.`fecha`) BETWEEN ? AND ?";
                        }
                        
                        
                }


                //Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
                if ( $fechaInicio != null && $fechaFinal != null && $timeRange == null)
                {
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " WHERE date(`view_ventas`.`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `usuario` ORDER BY `vendido` DESC LIMIT 1";

		

                return ViewVentasDAO::getResultArray( $qry_select, $params);
		

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

                $params = array();

                        
                //$qry_select = " SELECT `sucursal`.`descripcion` AS `nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `sucursal` WHERE `sucursal`.`id_sucursal` = `ventas`.`sucursal` ";
		$qry_select = "SELECT `sucursal`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas`";

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
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " WHERE date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_sucursal` ORDER BY `cantidad` DESC LIMIT 1";

                return ViewVentasDAO::getResultArray( $qry_select, $params);

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

                $params = array();

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas`";

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


                if ( $fechaInicio != null && $fechaFinal != null && $timeRange == null )
                {
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " WHERE date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                return ViewVentasDAO::getResultArray( $qry_select, $params);

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

	static function getVendedorMasProductivoSucursal( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
	{

		if ( $id_sucursal == null )
                {
                        return array( false, "Faltan parametros" );
                }
                
            
                $params = array($id_sucursal);

                //$qry_select = "SELECT `ventas`.`id_usuario`, `usuario`.`nombre`, SUM(`ventas`.`subtotal`) AS `Vendido` FROM `ventas`, `usuario` WHERE `ventas`.`id_usuario` = `usuario`.`id_usuario` AND `ventas`.`sucursal` = ?";                              
		$qry_select = "SELECT `usuario`, SUM(`subtotal`) AS `vendido` FROM `view_ventas` WHERE `id_sucursal` = ?";


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

                //Si existen los rangos de fechas, agregamos una linea al query para filtrar los resultados dentro de ese rango
                if ( $fechaInicio != null && $fechaFinal != null && $timeRange == null)
                {
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_usuario` ORDER BY `vendido` DESC LIMIT 1";
	


                return ViewVentasDAO::getResultArray( $qry_select, $params);

	
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
                
                $params = array($id_sucursal);

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas` WHERE `id_sucursal` = ? ";

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

                if ( $fechaInicio != null && $fechaFinal != null && $timeRange == null)
                {
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

		

                return ViewVentasDAO::getResultArray( $qry_select, $params);

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

                $params = array();

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas` WHERE `tipo_venta` = 'credito' ";

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

                if ( $fechaInicio != null && $fechaFinal != null && $timeRange == null)
                {
                        array_push($params, $fechaInicio);
                        array_push($params, $fechaFinal);
                        $dateRange .= " AND date(`fecha`) BETWEEN ? AND ?";
                        $qry_select .= $dateRange;
                }

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                return ViewVentasDAO::getResultArray( $qry_select, $params);
	


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


                $params = array();

                        
                //$qry_select = " SELECT `cliente`.`nombre`, SUM(`ventas`.`subtotal`) AS `Cantidad` FROM `ventas`, `cliente` WHERE `cliente`.`id_cliente` = `ventas`.`id_cliente` AND `ventas`.`sucursal` = ? ";
		$qry_select = "SELECT `cliente`, SUM(`subtotal`) AS `cantidad` FROM `view_ventas` WHERE `tipo_venta` = 'contado' ";

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

                $qry_select .= " GROUP BY `id_cliente` ORDER BY `cantidad` DESC LIMIT 1";

                return ViewVentasDAO::getResultArray( $qry_select, $params);
	


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

                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_ventas');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
                        if ( $id_sucursal != null )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $id_sucursal );
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

				//Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewVentasDAO::getResultArray( $completeQuery, $params);
				                                
                        }
                        else
                        {
                                return array( false, "No se obtuvieron las fechas correctamente");
                        }

                }
                else
                {
                        return array( false, "Faltan parametros");
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

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;

                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_ventas');

                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
                        //Si se mando el parametro id_sucursal lo agregamos a la clausula where
                        if ( $id_sucursal != null )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $id_sucursal );
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
                        $completeQuery = $functionQuery . " `tipo_venta` = 'contado' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewVentasDAO::getResultArray( $completeQuery, $params);
                                
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

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;

                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_ventas');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
                        //Si se mando el parametro id_sucursal lo agregamos a la clausula where
                        if ( $id_sucursal != null )
                        {
                                $functionQuery .= " WHERE `id_sucursal` = ? AND ";
                                array_push( $params, $id_sucursal);
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
                        $completeQuery = $functionQuery . " `tipo_venta` = 'credito' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                //Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewVentasDAO::getResultArray( $completeQuery, $params);
                                
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
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_ventas');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_ventas');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
                        if ( $id_cliente != null )
                        {
                                $functionQuery .= " WHERE `id_cliente` = ? AND ";
                                array_push( $params, $id_cliente );
                                array_push( $params, $datesArray[0] );
                                array_push( $params, $datesArray[1] );
                        }
                        else
                        {
                                return array( false, "Faltan parametros" );
                        }

                        //Formamos nuestro query completo
                        $completeQuery = $functionQuery . $qry_select ;


                        if ( $qry_select != false )
                        {                       
                                //Todo salio bien asi que regresamos el arreglo con el resultado
				return ViewVentasDAO::getResultArray( $completeQuery, $params);
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
