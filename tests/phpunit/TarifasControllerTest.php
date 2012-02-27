<?php


date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 71;

require_once("../../server/bootstrap.php");


class TarifasControllerTest extends PHPUnit_Framework_TestCase {
	
	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}
	
	

	public function testNuevaTarifaVenta(){
		
		$monedas = MonedaDAO::getAll();
		
		if(sizeof($monedas) == 0){
			throw new Exception("No hay monedas");
		}
		
		//crear nueva tarifa de venta
		$tv = TarifasController::Nueva(
						$monedas[0]->getIdMoneda(),
						"tarifav" . time(),
						"venta"
						
					);
		
		$this->assertInternalType("int", $tv["id_tarifa"]);
	}
	
	
	
	
	public function testNuevaTarifaCompra(){
		
		$monedas = MonedaDAO::getAll();
		
		if(sizeof($monedas) == 0){
			throw new Exception("No hay monedas");
		}
		
		//crear nueva tarifa de venta
		$tc = TarifasController::Nueva(
						$monedas[0]->getIdMoneda(),
						"tarifac" . time(),
						"compra"
						
					);
		
		$this->assertInternalType("int", $tc["id_tarifa"]);
	}	
	
	
	
	public function testEditarTiposDeTarifas(){
		$monedas = MonedaDAO::getAll();

		//crear nueva tarifa de venta
		$tv = TarifasController::Nueva(
						$monedas[0]->getIdMoneda(),
						"tarifac" . time(),
						"compra"
					);
		

		//crear nueva tarifa de compra
		$tc = TarifasController::Nueva(
						$monedas[0]->getIdMoneda(),
						"tarifac" . time(),
						"compra"
					);
		
		//editar tarifa compra
		TarifasController::Editar(
					$id_tarifa = $tc["id_tarifa"], 
					$default = null, 
					$fecha_fin = null, 
					$fecha_inicio = null, 
					$formulas = null, 
					$id_moneda = null, 
					$nombre = "_" . $tc["nombre"], 
					$tipo_tarifa = "venta"
				);
				
		//Editar la tarifa de venta
		TarifasController::Editar(
					$id_tarifa = $tv["id_tarifa"], 
					$default = null, 
					$fecha_fin = null, 
					$fecha_inicio = null, 
					$formulas = null, 
					$id_moneda = null, 
					$nombre = "_" . $tc["nombre"], 
					$tipo_tarifa = "compra"
				);		
	}
}