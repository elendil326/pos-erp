<?php

require_once ('Estructura.php');
require_once("base/view_detalle_compra.dao.base.php");
require_once("base/view_detalle_compra.vo.base.php");

require_once("../server/misc/reportesUtils.php");
/** ViewDetalleCompra Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * <b>recuperar</b> instancias de objetos {@link ViewDetalleCompra }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ViewDetalleCompraDAO extends ViewDetalleCompraDAOBase
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
        *       Obtiene los datos para generar una grafica de los productos mas comprados en general.
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


	static function graficaProductosMasComprados($timeRange, $tipo_venta, $id_sucursal, $fechaInicio, $fechaFinal )
	{
		//$array = getDateRangeGraphics('semana');
                $params = array();

                if( $timeRange != null )
                {
                        $dateInterval = $timeRange;
                        $params = array();
                        //Obtenemos el select del query para obtener las ventas
                       $functionQuery = "SELECT `id_producto` AS `x`, ROUND(SUM(`cantidad` * `precio`)) AS `y`, `denominacion` AS `label` FROM `view_detalle_compra`  WHERE ";
			
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
				return ViewDetalleComprasDAO::getResultArray( $completeQuery, $params);
                                
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
