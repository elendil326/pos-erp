<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 123;

require_once("../../server/bootstrap.php");


class VentasControllerTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}

	public function testNueva(){
		VentasController::Nueva(
			        /*$descuento, 
			        $id_comprador_venta, 
			        $impuesto, 
			        $subtotal, 
			        $tipo_venta, 
			        $total, 
			        $datos_cheque = null, 
			        $detalle_orden = null, 
			        $detalle_paquete = null, 
			        $detalle_venta = null, 
			        $id_sucursal = null, 
			        $saldo = "0", 
			        $tipo_de_pago = null*/
			0, 
	        1, 
	        null, 
	        1, 
	        "contado", 
	       	1
	    );
	}



}