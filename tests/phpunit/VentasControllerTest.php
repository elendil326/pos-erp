<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 123;

require_once("../../server/bootstrap.php");


class VentasControllerTest extends PHPUnit_Framework_TestCase {

	private $current_client;

	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
		$this->current_client = $this->nuevoCliente();
		$this->current_client = $this->current_client["id_cliente"];

	}

	private function nuevoCliente(){
		return ClientesController::Nuevo( "02558a70324e7c4f269c69825450cec8" );
	}
	

	/**
     * @expectedException InvalidDataException
     */
	public function testNuevaVentaSinDetalles(){

		$detalle_venta = new stdClass;

		VentasController::Nueva(
	        /*$descuento*/  			0,
	        /*$id_comprador_venta*/ 	$this->current_client, 
	        /*$impuesto*/ 				0,
	        /*$subtotal*/ 				1,
	        /*$tipo_venta*/ 			"contado",
	        /*$total*/ 					1,
	        /*$datos_cheque = null*/	null,
	        /*$detalle_orden = null*/ 	null,
	        /*$detalle_paquete = null*/	null,
	        /*$detalle_venta = null*/	$detalle_venta
	        /*$id_sucursal = null*/
	        /*$saldo = "0"*/
	        /*$tipo_de_pago = null*/
	    );
	}



}