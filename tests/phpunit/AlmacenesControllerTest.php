<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 123;

require_once("../../server/bootstrap.php");
require_once("Utils.php");


class AlmacenControllerTest extends PHPUnit_Framework_TestCase {

	

	protected function setUp(){
		Logger::log("-----------------------------");
		//$r = SesionController::Iniciar(123, 1, true);

	}



	
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
				Logger::debug($tipo["descripcion"]);
				Logger::log("Ya encontre el repetido, procedo a desactivar");
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





	//Elimina un tipo de almacen
	 public function testTipoBuscarYEditar(){
		
	}







	public function testNuevo(){
				/* id_empresa, 		*/
				/* id_sucursal, 	*/
				/* id_tipo_almacen, */
				/* nombre, 			*/
				/* descripcion=null */
	}


	public function testBuscar(){
		
	}

	//Buscar almacenes
	public function testDesactivar(){
		
	}

	//Elimina un almacen
	public function testEditar(){
		
	}

	//Edita la informacion de un almacen
	public function testEntrada(){
		
	}

	

	//Crear un nuevo almacen en una sucursal
	public function testSalida(){
		
	}









	//Crea un nuevo tipo de almacen
	 public function testTraspasoBuscar(){
		
	}

	//Lista los traspasos
	 public function testTraspasoCancelar(){
		
	}

	//Cancela un traspaso
	 public function testTraspasoEditar(){
		
	}

	//Edita la informacion de un traspaso
	 public function testTraspasoEnviar(){
		
	}

	//Envia un traspaso de productos
	 public function testTraspasoProgramar(){
		
	}

	//Crea un registro de traspaso
	 public function testTraspasoRecibir(){
		
	}
		
}