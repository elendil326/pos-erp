<?php

require_once("controller/clientes.controller.php");
require_once("controller/inventario.controller.php");
require_once("controller/autorizaciones.controller.php");


class ContabilidadController{
	
	
	/**
      * Revisar persistencia.
      *
      * Esta funcion recibe un hash md5 generado por
      * {POS::getPersistencyHash} y regresa verdadero si
      * es que existe un cambio desde que se genero ese
      * hash. Regresa falso si no.
      *
      * @param String Una cadena que contiene un hash MD5
      * @return boolean Verdadero si el hash actual del sistema es igual al argumento, falso si no.
      **/
	public static function getIngresosDiarios(  ){
		$flujo = array();


		/* * *****************************************
		 * Fecha desde el ultimo corte
		 * ****************************************** */
		$corte = new Corte();
		$corte->setIdSucursal($_REQUEST['id']);

		$cortes = CorteDAO::getAll(1, 1, 'fecha', 'desc');



		if (sizeof($cortes) == 0) {
		    echo "<div align=center>No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.</div><br>";

		    $fecha = $sucursal->getFechaApertura();
		} else {

		    $corte = $cortes[0];
		    echo "Fecha de ultimo corte: <b>" . $corte->getFecha() . "</b><br>";
		    $fecha = $corte->getFecha();
		}


		$now = new DateTime("now");
		$hoy = $now->format("Y-m-d H:i:s");

		/* * *****************************************
		 * Buscar los gastos
		 * Buscar todos los gastos desde la fecha inicial
		 * **************************************** */
		$foo = new Gastos();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($_REQUEST['id']);

		$bar = new Gastos();
		$bar->setFecha($hoy);

		$gastos = GastosDAO::byRange($foo, $bar);


		foreach ($gastos as $g) {
		    array_push($flujo, array(
		        "tipo" => "gasto",
		        "concepto" => $g->getConcepto(),
		        "monto" => $g->getMonto() * -1,
		        "usuario" => $g->getIdUsuario(),
		        "fecha" => $g->getFecha()
		    ));
		}


		/* * *****************************************
		 * Ingresos
		 * Buscar todos los ingresos desde la fecha inicial
		 * ****************************************** */
		$foo = new Ingresos();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($_REQUEST['id']);

		$bar = new Ingresos();
		$bar->setFecha($hoy);

		$ingresos = IngresosDAO::byRange($foo, $bar);

		foreach ($ingresos as $i) {
		    array_push($flujo, array(
		        "tipo" => "ingreso",
		        "concepto" => $i->getConcepto(),
		        "monto" => $i->getMonto(),
		        "usuario" => $i->getIdUsuario(),
		        "fecha" => $i->getFecha()
		    ));
		}


		/* * *****************************************
		 * Ventas
		 * Buscar todas la ventas a contado para esta sucursal desde esa fecha
		 * ****************************************** */
		$foo = new Ventas();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($_REQUEST['id']);
		$foo->setTipoVenta('contado');

		$bar = new Ventas();
		$bar->setFecha($hoy);

		$ventas = VentasDAO::byRange($foo, $bar);


		//las ventas
		foreach ($ventas as $i) {
		    array_push($flujo, array(
		        "tipo" => "venta",
		        "concepto" => "<a href='ventas.php?action=detalles&id=" . $i->getIdVenta() . "'>Venta de contado</a>",
		        "monto" => $i->getPagado(),
		        "usuario" => $i->getIdUsuario(),
		        "fecha" => $i->getFecha()
		    ));
		}



		/* * *****************************************
		 * Abonos
		 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
		 * ****************************************** */
		$query = new PagosVenta();
		$query->setIdSucursal($_REQUEST["id"]);
		$query->setFecha($fecha);

		$queryE = new PagosVenta();
		$queryE->setFecha($hoy);


		$results = PagosVentaDAO::byRange($query, $queryE);

		foreach ($results as $pago) {
		    array_push($flujo, array(
		        "tipo" => "abono",
		        "concepto" => "<a href='ventas.php?action=detalles&id=" . $pago->getIdVenta() . "'>Abono a venta</a>",
		        "monto" => $pago->getMonto(),
		        "usuario" => $pago->getIdUsuario(),
		        "fecha" => $pago->getFecha()
		    ));
		}
	}
	
	

}



