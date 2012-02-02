<?php 
	$_GET["_instance_"] = 123;
	
	require_once("../../server/bootstrap.php");

	class ClientesControllerTests extends PHPUnit_Framework_TestCase {
		
		
		/**
		 * 
		 * Actual Tests
		 * */
		public function testNuevoCliente(){
			ClientesController::nuevo("Alan Gonzalez");
		}

		
	}

