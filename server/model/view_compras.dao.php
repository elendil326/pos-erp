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

                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las compras
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_compras');
                        

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
                                
				return ViewComprasDAO::getResultArray( $completeQuery, $params );
                                
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

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_compras');

                        

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
                        $completeQuery = $functionQuery . " `tipo_compra` = 'contado' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                return ViewComprasDAO::getResultArray( $completeQuery, $params );
                                
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

                //$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_compras');
                        

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
                        $completeQuery = $functionQuery . " `tipo_compra` = 'credito' AND " . $qry_select ;

                        
                        if ( $qry_select != false )
                        {                       
                                return ViewComprasDAO::getResultArray( $completeQuery, $params );
                                
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
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las compras
                        $functionQuery = reportesUtils::selectIntervalo($dateInterval, 'view_compras');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'view_compras');
                        

                        //Si se escogieron las fechas manualmente, se sobreescriben
                        if ( $fechaInicio != null && $fechaFinal != null )
                        {
                                $datesArray[0] = $fechaInicio;
                                $datesArray[1] = $fechaFinal;
                        }
                        
                        if ( $id_proveedor != null )
                        {
                                $functionQuery .= " WHERE `id_proveedor` = ? AND ";
                                array_push( $params, $id_proveedor );
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
                                return ViewComprasDAO::getResultArray( $completeQuery, $params );
                                
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
