<?php

	date_default_timezone_set ( "America/Mexico_City" );

	if(!defined("BYPASS_INSTANCE_CHECK"))
		define("BYPASS_INSTANCE_CHECK", false);

	$_GET["_instance_"] = 123;

	require_once("../../server/bootstrap.php");


	class ClientesControllerTests extends PHPUnit_Framework_TestCase {
		
		protected function setUp(){
			Logger::log("-----------------------------");
			$r = SesionController::Iniciar(123, 1, true);
		}
		
		public function testNuevaClasificacionCliente(){
			$c = ClasificacionClienteDAO::getByPK(1);
			
			if(!is_null($c)){
				ClasificacionClienteDAO::delete($c);
				
			}

			$n_c = new ClasificacionCliente();
			$n_c->setIdClasificacionCliente(1);
			$n_c->setClaveInterna('Default');
			$n_c->setNombre('Default');
			$n_c->setIdTarifaVenta(1);
			$n_c->setIdTarifaCompra(1);			
			
			ClasificacionClienteDAO::save( $n_c );
		}
		
		
		public function testNuevoCliente(){
			$c = ClientesController::nuevo("Alan Gonzalez");
			$this->assertInternalType("int" , $c["id_cliente"]);
		}

		public function testNuevoClienteConMismoNombre(){
			ClientesController::nuevo("Alan Gonzalez");
		}
		
	}

