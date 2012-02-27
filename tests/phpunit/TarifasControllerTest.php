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
	
	
	//probar una nueva tarifa
	public function testNuevaTarifa(){
		
		$monedas = MonedaDAO::getAll();
		
		if(sizeof($monedas) == 0){
			throw new Exception("No hay monedas");
		}
		
		//crear nueva tarifa de venta
		$tv = TarifasController::Nueva(
						$monedas[0]->getIdMoneda(),
						"tarifa" . time(),
						"venta"
						
					);
		
		$this->assertInternalType("int", $tv["id_tarifa"]);
		
		
	}
	
	
	
}