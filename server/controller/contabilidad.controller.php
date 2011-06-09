<?php

require_once("controller/clientes.controller.php");
require_once("controller/inventario.controller.php");
require_once("controller/autorizaciones.controller.php");


class ContabilidadController{
	
	
	
	public static function getBalancePorSucursal($id_sucursal){
		$ingresos_diarios = ContabilidadController::getIngresosDiarios($id_sucursal );
		$gastos_diarios = ContabilidadController::getGastosDiarios($id_sucursal );

		$bal = array();
		$last_one = 0;
		for ($index=0; $index < sizeof($ingresos_diarios); $index++) { 
			array_push($bal, array(
				"fecha" => $ingresos_diarios[$index]["fecha"],
				"value" => $ingresos_diarios[$index]["value"] - $gastos_diarios[$index]["value"] + $last_one
			));
			
			$last_one = $ingresos_diarios[$index]["value"] - $gastos_diarios[$index]["value"] + $last_one;
		}
		
		return $bal;
		
	}
	
	/**
      * Calcuras gastos diarios por sucursal.
      *
      *
      * @param int el id de la sucursal
      **/
	public static function getGastosDiarios(  $id_sucursal ){

		$sucursal = SucursalDAO::getByPK( $id_sucursal );

	    $fecha = $sucursal->getFechaApertura();
		
		$flujo = array();


		$now = new DateTime("now");
		$hoy = $now->format("Y-m-d H:i:s");


		/* * *****************************************
		 * Egresos
		 * Buscar todos los ingresos desde la fecha inicial
		 * ****************************************** */
		$foo = new Gastos();
		$foo->setFecha($fecha);
		$foo->setIdSucursal( $id_sucursal );

		$bar = new Gastos();
		$bar->setFecha($hoy);

		$Gastos = GastosDAO::byRange($foo, $bar);

		foreach ($Gastos as $i) {
		    array_push($flujo, array(
		        "monto" => $i->getMonto(),
		        "fecha" => date("Y-m-d", strtotime( $i->getFecha() )  )
		    ));
		}


		/* * *****************************************
		 * Ventas a credito
		 * Buscar todas la ventas a contado para esta sucursal desde esa fecha
		 * ****************************************** */
		$foo = new Ventas();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($id_sucursal);
		$foo->setTipoVenta('credito');

		$bar = new Ventas();
		$bar->setFecha($hoy);

		$ventas = VentasDAO::byRange($foo, $bar);

		//las ventas
		foreach ($ventas as $i) {
		    array_push($flujo, array(
		        "monto" => $i->getTotal(),
		        "fecha" => date("Y-m-d", strtotime( $i->getFecha() )  )
		    ));
		}


		/* * *****************************************
		 * Prestamos
		 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
		 * ****************************************** */
		
		

		/* * *****************************************
		 * compras a proveedores
		 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
		 * ****************************************** */
		$query = new CompraSucursal();
		$query->setIdSucursal($id_sucursal);
		$query->setFecha($fecha);

		$queryE = new CompraSucursal();
		$queryE->setFecha($hoy);


		$results = CompraSucursalDAO::byRange($query, $queryE);

		foreach ($results as $compra) {
		    array_push($flujo, array(
		        "monto" => $compra->getTotal(),
		        "fecha" => date("Y-m-d", strtotime( $compra->getFecha() )  )
		    ));
		}
		
		//ok, todos los ingresos ya estan en $flujo,
		//ahora solo hay que ordenarlos por la fecha que tienen
		return ContabilidadController::groupArrayByDate( $flujo, $fecha );

	}//getGastosDiarios()
	
	
	
	
	/**
      * Calcuras ingresos diarios por sucursal.
      *
      *
      * @param int el id de la sucursal
      **/
	public static function getIngresosDiarios(  $id_sucursal ){

		$sucursal = SucursalDAO::getByPK( $id_sucursal );

	    $fecha = $sucursal->getFechaApertura();
		
		$flujo = array();


		$now = new DateTime("now");
		$hoy = $now->format("Y-m-d H:i:s");


		/* * *****************************************
		 * Ingresos
		 * Buscar todos los ingresos desde la fecha inicial
		 * ****************************************** */
		$foo = new Ingresos();
		$foo->setFecha($fecha);
		$foo->setIdSucursal( $id_sucursal );

		$bar = new Ingresos();
		$bar->setFecha($hoy);

		$ingresos = IngresosDAO::byRange($foo, $bar);

		foreach ($ingresos as $i) {
		    array_push($flujo, array(
		        "monto" => $i->getMonto(),
		        "fecha" => date("Y-m-d", strtotime( $i->getFecha() )  )
		    ));
		}


		/* * *****************************************
		 * Ventas a contado
		 * Buscar todas la ventas a contado para esta sucursal desde esa fecha
		 * ****************************************** */
		$foo = new Ventas();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($id_sucursal);
		$foo->setTipoVenta('contado');

		$bar = new Ventas();
		$bar->setFecha($hoy);

		$ventas = VentasDAO::byRange($foo, $bar);

		//las ventas
		foreach ($ventas as $i) {
		    array_push($flujo, array(
		        "monto" => $i->getPagado(),
		        "fecha" => date("Y-m-d", strtotime( $i->getFecha() )  )
		    ));
		}


		/* * *****************************************
		 * Prestamos
		 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
		 * ****************************************** */
		
		

		/* * *****************************************
		 * Abonos
		 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
		 * ****************************************** */
		$query = new PagosVenta();
		$query->setIdSucursal($id_sucursal);
		$query->setFecha($fecha);

		$queryE = new PagosVenta();
		$queryE->setFecha($hoy);


		$results = PagosVentaDAO::byRange($query, $queryE);

		foreach ($results as $pago) {
		    array_push($flujo, array(
		        "monto" => $pago->getMonto(),
		        "fecha" => date("Y-m-d", strtotime( $pago->getFecha() )  )
		    ));
		}
		
		//ok, todos los ingresos ya estan en $flujo,
		//ahora solo hay que ordenarlos por la fecha que tienen
		return ContabilidadController::groupArrayByDate( $flujo, $fecha );

	}//getIngresosDiarios()
	
	
	
	
	private static function compareDatesFromObj($o1, $o2){
		if(strtotime($o1["fecha"]) == strtotime($o2["fecha"])) return 0;
		return strtotime($o1["fecha"]) > strtotime($o2["fecha"]);
	}
	
	
	
	private static function groupArrayByDate( $flujo_array, $date_start ){
		
		usort( $flujo_array, "ContabilidadController::compareDatesFromObj" );

		//esa el la fecha que comenzare a iterar
		$dayIndex =  date("Y-m-d", strtotime( $date_start )  );

		//the day the loop will end
		$tomorrow = date("Y-m-d", strtotime("+1 day",  time()));

		$arranged_data = array();
		//print( "comenzando en el array " . $flujo_array[0]["fecha"]);

		$where_i_left = 0;

		while( $tomorrow != $dayIndex ){

			$total_de_hoy = 0;
			$found_some_data = false;
			$next_day = date("Y-m-d", strtotime("+1 day", strtotime($dayIndex) ) );
			
			for ($index = $where_i_left; $index < sizeof($flujo_array); $index++) { 
				
				if( $flujo_array[$index]["fecha"] == $dayIndex){
					$total_de_hoy += $flujo_array[$index]["monto"];
					$found_some_data = true;
				}
				
				//si ya estoy en el dia de manana segun ese dia, a la verga
				if($flujo_array[$index]["fecha"] == $next_day ){
					$where_i_left = $index;
					break;					
				}
			}

			
			array_push( $arranged_data, array(
				"fecha" => $dayIndex,
				"value" => $total_de_hoy
			) );
			
			$dayIndex = date("Y-m-d", strtotime("+1 day", strtotime($dayIndex)));
		}
		
		return $arranged_data;
	}

}



