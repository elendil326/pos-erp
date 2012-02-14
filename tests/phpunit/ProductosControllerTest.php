<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 123;

require_once("../../server/bootstrap.php");





class ProductosControllerTest extends PHPUnit_Framework_TestCase {

/*
	public function testBuscar(){
		//insertar un nuevo producto
		$p = ProductosController::Buscar( "b3af409bb8423187c75e6c7f5b683908" );

		$this->assertInternalType(  );
		if($p["numero"])
	}
*/


	public function testNuevo(){
		//insertar un nuevo producto
		$p = ProductosController::Nuevo( 
			true, 
			"ASDF", 
			false, 
			"ASDF", 
			"precio_estandar",  //precio_estandar
			"ASDF" );
	}
}