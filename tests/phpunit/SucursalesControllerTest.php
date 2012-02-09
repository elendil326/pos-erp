<?php


date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 123;

require_once("../../server/bootstrap.php");





class SucursalesControllerTest extends PHPUnit_Framework_TestCase {
	
	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}
	
	

	public function testNueva(){
		
		$direccion = Array(
			"calle"  			=> "Monte Balcanes",
	        "numero_exterior"   => "107",
	        "colonia"  			=> "Arboledas",
	        "id_ciudad"  		=> 334,
	        "codigo_postal"  	=> "38060",
	        "numero_interior"  	=> null,
	        "referencia"  		=> "Calle cerrada",
	        "telefono1"  		=> "4616149974",
	        "telefono2"			=> "45*451*454"
		);
		
		$empresa = SucursalesController::Nueva($direccion, "Las Fuentes");
		
		$this->assertInternalType('int', $empresa["id_sucursal"]);
		
	}
	

	
}