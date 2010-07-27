<?php

/**
*
*Clase que guarda diferentes utilidades que sirven para
*la generación de los datos para los reportes.
*@author Luis Michel <luismichel@computer.org>
*
*/


class reportesUtils
{

	/**
        *       Obtenemos dos fechas dentro de un rango de tiempo. Se refiere la ultima semana, mes, año.
        *	@ignore
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param $interval {String} Es una cadena con el tipo de rango que se requiere, puede ser: semana, mes, year
        *       @return {Array} Un arreglo con dos fechas 
        */

	static function getDateRange($dateInterval)
	{
		$datesArray = array();
                $currentDate = getdate();
                $dateToday = $currentDate['year'].'-'.$currentDate['mon'].'-'.$currentDate['mday'];

                switch($dateInterval)
                        {
                                case 'semana':  $fecha = date_create( $dateToday );
                                                //$fecha = date_create("2010-07-14");
                                                date_sub($fecha, date_interval_create_from_date_string('7 days'));
                                                $dateWeekBefore = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateWeekBefore);
                                                array_push($datesArray, $dateToday);
        
                                                return($datesArray);

                                                break;
                                /************************************************************************/

                                case 'mes':     $fecha = date_create( $dateToday );
                                                //$fecha = date_create("2010-07-14");
                                                date_sub($fecha, date_interval_create_from_date_string('1 month'));
                                                $dateWeekBefore = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateWeekBefore);
                                                array_push($datesArray, $dateToday);

                                                return($datesArray);

                                                break;
                                /************************************************************************/

                                case 'year':    $fecha = date_create( $dateToday );
                                                //$fecha = date_create("2010-07-14");
                                                date_sub($fecha, date_interval_create_from_date_string('1 year'));
                                                $dateWeekBefore = date_format($fecha, 'Y-m-d');
                                                array_push($datesArray, $dateWeekBefore);
                                                array_push($datesArray, $dateToday);

                                                return($datesArray);

                                                break;
                                /************************************************************************/

                                default:        return false;
                        }

	}


	/**
        *       Obtenemos dos fechas dentro de un rango de tiempo. Se refiere la ultima semana, mes, año.
        *	@ignore
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param $interval {String} Es una cadena con el tipo de rango que se requiere, puede ser: semana, mes, year
        *       @return {Array} Un arreglo con dos fechas 
        */

	static function getDateRangeGraphics($dateInterval)
	{

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

                                default:        $datesArray = false;

                        }
                return $datesArray;
	
	}

	

	/**
        *       Genera la parte final de nuestro query, la parte donde filtra entre 2 fechas
        *	@ignore
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param $dateInterval {String} Es una cadena con el tipo de rango que se requiere, puede ser: semana, mes, year
	*	@param $table {String} El nombre de la tabla de donde queremos que se obtengan los datos
        *       @return {String} Una cadena con una parte de la clausula WHERE de nuestra consulta ( BETWEEN )
        */


	static function betweenDatesQueryPart( $dateInterval, $table )
        {


                                /*
                                *       FORMAMOS EL QUERY PARA LA SEMANA
                                */
                                if ( $dateInterval == 'semana')
                                {
                                        $qry_select = " date(`fecha`) BETWEEN ? AND ?";
        
                                        $qry_select .= " GROUP BY DAYOFWEEK(`fecha` )";
                                }

                                /*
                                *       FORMAMOS EL QUERY PARA EL MES
                                */

                                if ( $dateInterval == 'mes')
                                {
                                        $qry_select = " date(`fecha`) BETWEEN ? AND ?";

                                        $qry_select .= " GROUP BY DAYOFMONTH(`fecha` )";


                                }
                                //var_dump($params);

                                if ($dateInterval == 'year')
                                {
                                        $qry_select = " date(`fecha`) BETWEEN ? AND ?";

                                        $qry_select .= " GROUP BY MONTH(`fecha` )";
                                }

                                
                                return $qry_select;


	}	

	/**
        *       Genera la parte del SELECT de nuestro query de nuestras tabla Compras y Ventas 
        *	@ignore
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param $interval {String} Es una cadena con el tipo de rango que se requiere, puede ser: semana, mes, year
	*	@param $table {String} El nombre de la tabla de donde queremos que se obtengan los datos
        *       @return {String} Una cadena con una parte de la consulta, clausula SELECT
        */


	static function selectIntervalo( $interval, $table )
        {
                
                //La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
                        switch( $interval ) 
                        {
                                case 'semana'   : $qry = "SELECT DAYOFWEEK(`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYNAME(`fecha`) AS `label` FROM `".$table."`  ";
                                                break;
                                case 'mes'      : $qry = "SELECT DAYOFMONTH(`fecha`) AS `x`, SUM(`subtotal`) AS `y`, DAYOFMONTH(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                case 'year'     : $qry = "SELECT MONTH(`fecha`) AS `x`, SUM(`subtotal`) AS `y`, MONTHNAME(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                default: break;
                        }

                return $qry;
        
        }



	/**
        *       Genera la parte del SELECT de nuestro query de nuestras tabla detalle_compras y detalle_ventas 
        *	@ignore
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param $interval {String} Es una cadena con el tipo de rango que se requiere, puede ser: semana, mes, year
	*	@param $table {String} El nombre de la tabla de donde queremos que se obtengan los datos
        *       @return {String} Una cadena con una parte de la consulta, clausula SELECT
        */

	static function selectDetallesIntervalo( $interval, $table )
        {
                
                //La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
                        switch( $interval ) 
                        {
                                case 'semana'   : $qry = "SELECT DAYOFWEEK(`fecha`) AS `x`, ROUND(SUM(`cantidad` * `precio`), 2) AS `y`, DAYNAME(`fecha`) AS `label` FROM `".$table."`  ";
                                                break;
                                case 'mes'      : $qry = "SELECT DAYOFMONTH(`fecha`) AS `x`, ROUND(SUM(`cantidad` * `precio`), 2) AS `y`, DAYOFMONTH(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                case 'year'     : $qry = "SELECT MONTH(`fecha`) AS `x`, ROUND(SUM(`cantidad` * `precio`), 2) AS `y`, MONTHNAME(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                default: break;
                        }

                return $qry;
        
        }


	/**
        *       Genera la parte del SELECT de nuestro query de nuestras tabla gastos e ingresos
        *	@ignore
        *       @author Luis Michel <luismichel@computer.org>
        *       @static
        *       @access public
	*	@param $interval {String} Es una cadena con el tipo de rango que se requiere, puede ser: semana, mes, year
	*	@param $table {String} El nombre de la tabla de donde queremos que se obtengan los datos
        *       @return {String} Una cadena con una parte de la consulta, clausula SELECT
        */

	static function selectEfectivoIntervalo( $interval, $table )
        {
                
                //La clausula SELECT, FROM y WHERE la agregamos nosotros, esta es la que cambia
                        switch( $interval ) 
                        {
                                case 'semana'   : $qry = "SELECT DAYOFWEEK(`fecha`) AS `x`, SUM(`monto`) AS `y`, DAYNAME(`fecha`) AS `label` FROM `".$table."`  ";
                                                break;
                                case 'mes'      : $qry = "SELECT DAYOFMONTH(`fecha`) AS `x`, SUM(`monto`) AS `y`, DAYOFMONTH(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                case 'year'     : $qry = "SELECT MONTH(`fecha`) AS `x`, SUM(`monto`) AS `y`, MONTHNAME(`fecha`) AS `label` FROM `".$table."` ";
                                                break;
                                default: break;
                        }

                return $qry;
        
        }


}
?>
