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
		$r = SesionController::Iniciar(123, 1, true);

	}


	
	//Edita un tipo de almacen
	public function testTipoNuevo(){

		$a = AlmacenController::Nuevo(
				/* id_empresa, 		*/
				/* id_sucursal, 	*/
				/* id_tipo_almacen, */
				/* nombre, 			*/
				/* descripcion=null */
			);	
	}


	//Envia productos fuera del almacen
	public function testTipoBuscar(){
		
	}

	//Imprime la lista de tipos de almacen
	public function testTipoDesactivar(){
		
	}

	//Elimina un tipo de almacen
	 public function testTipoEditar(){
		
	}







	public function testNuevoAlmacen(){
		
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

	//Surtir una sucursal
	public function testNuevo(){
		
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