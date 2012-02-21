<?php


date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 71;

require_once("../../server/bootstrap.php");





class ServiciosControllerTest extends PHPUnit_Framework_TestCase {
	
	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}
	
	
	
	public function testBuscarEliminarYNuevo(){
		
		//buscar el servicio que se llama prestamo

		$servs = ServiciosController::Buscar();
		
		$this->assertEquals( $servs["numero_de_resultados"], sizeof($servs["resultados"]) );
		
		for ($i=0; $i < $servs["numero_de_resultados"]; $i++) { 
			$s = $servs["resultados"][$i]->asArray();
			if($s["nombre_servicio"] == "prestamo"){
				ServiciosController::Eliminar( $s["id_servicio"] );
			}
		}
		
		$s = ServiciosController::Nuevo(
			"CA01", 
			false, 
			0, 
			"precio", 
			"prestamo",
			true, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			null, 
			0
		);
	}

	
}