<?php

	
/**
*
*	Controller para la vista view_gastos
*
*	Contiene funciones que nos generan información relevante para la generación de gráficas de gastos
*	@author Luis Michel <luismichel@caffeina.mx>
*/

require_once("../server/model/view_gastos.dao.php");

	
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


	function DataGastos( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal )
	{
		
		$data = ViewGastosDao::getDataGastos( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal );

		if ( $data[0] != false )
		{
			$result = "{ success: true, datos: ".json_encode($data)."}";
		}
		else
		{
			$result = "{ success: false, error: ".$data[1]."}";
		}

		return $result;

	}


	switch($args['action'])
	{

		/*************VIEW_GASTOS*******************/


	case '801' : //graficaGastos
	
		$timeRange = null;
		$fechaInicio = null;
		$fechaFinal = null;
		$id_sucursal = null;


		if ( isset($args['dateRange']) )
		{
			$timeRange = $args['dateRange'];
		}
		

		if ( isset($args['id_sucursal']) )
		{
			$id_sucursal = $args['id_sucursal'];
		}

		if ( isset($args['fecha-inicio']) && isset($args['fecha-final']) )
		{
			$fechaInicio = $args['fecha-inicio'];
			$fechaFinal = $args['fecha-final'];	
		}

		$result = DataGastos( $timeRange, $id_sucursal, $fechaInicio, $fechaFinal );
		echo $result;
		break;
			

	}


?>
