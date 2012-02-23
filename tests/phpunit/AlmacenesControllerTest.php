<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 71;

require_once("../../server/bootstrap.php");
require_once("Utils.php");


class AlmacenControllerTest extends PHPUnit_Framework_TestCase {

	

	protected function setUp(){
		Logger::log("-----------------------------");
		//$r = SesionController::Iniciar(123, 1, true);

	}


	/**
	*
	*
	*	Almacenes
	*
	**/

	
	//Imprime la lista de tipos de almacen
	public function testTipoBuscarYDesactivar(){

		$r = AlmacenesController::BuscarTipo();

		$this->assertInternalType("int", $r["numero_de_resultados"]);

		$this->assertEquals( $r["numero_de_resultados"], count($r["resultados"]) );

		if($r["numero_de_resultados"] == 0){
			return;
		}


		foreach ($r["resultados"] as $tipo) {
			$tipo = $tipo->asArray();
			if($tipo["descripcion"] == "1dee80c7d5ab2c1c90aa8d2f7dd47256"){
				//ya existe este tipo para testing, hay que desactivarlo

				Logger::testerLog("Ya encontre el repetido, procedo a desactivar");
				$d = AlmacenesController::DesactivarTipo( $tipo["id_tipo_almacen"] );
			}
		}

		//volvamos a buscar y esperemos que ya no exista
		$r = AlmacenesController::BuscarTipo();

		$found = false;

		foreach ($r["resultados"] as $tipo) {
			$tipo = $tipo->asArray();
			if($tipo["descripcion"] == "1dee80c7d5ab2c1c90aa8d2f7dd47256"){
				//ya existe este tipo para testing, hay que desactivarlo
				$found = true;
			}
		}

		$this->assertFalse($found);
	}




	//Edita un tipo de almacen
	public function testTipoNuevo(){

		$a = AlmacenesController::NuevoTipo("1dee80c7d5ab2c1c90aa8d2f7dd47256");	
		$this->assertInternalType("int", $a["id_tipo_almacen"]);

	}



	/**
     * @expectedException BusinessLogicException
     */
	public function testTipoNuevoRepetido(){
		$a = AlmacenesController::NuevoTipo("1dee80c7d5ab2c1c90aa8d2f7dd47256");	
	}


	public function testBuscar(){
		
	}

	public function testDesactivar(){
		
	}

	public function testEditar(){
		
	}


	/**
	*
	*
	*	Lotes
	*
	**/
	public function testLoteNuevo(){
		AlamacenController::NuevoLote(  );
	}

	public function testLoteEntrada(){
		
	}



	public function testLoteSalida(){
		
	}

	public function testLoteTraspasoBuscar(){
		
	}

	public function testLoteTraspasoCancelar(){
		
	}

	public function testLoteTraspasoEditar(){
		
	}

	public function testLoteTraspasoEnviar(){
		
	}

	public function testLoteTraspasoProgramar(){
		
	}

	public function testLoteTraspasoRecibir(){
		
	}

	public function testNuevo(){
		
	}


	/*public function testTipoBuscar(){}
	public function testTipoDesactivar(){}
	public function testTipoEditar(){}
	public function testTipoNuevo(){}*/
		
}