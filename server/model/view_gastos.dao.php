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

                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener el gasto
                        $functionQuery = reportesUtils::selectEfectivoIntervalo($dateInterval, 'gastos');

                        //getDateRangeGraphics nos regresa un arreglo con todas las fechas para la consulta, y el ultimo dato es la consulta
                        $datesArray = reportesUtils::getDateRangeGraphics($dateInterval);
                        $qry_select = reportesUtils::betweenDatesQueryPart($dateInterval, 'gastos');
                        

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
                                return ViewGastosDAO::getResultArray( $completeQuery, $params );
                                
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
