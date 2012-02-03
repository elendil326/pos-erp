<?php

date_default_timezone_set ( "America/Mexico_City" );

define("BYPASS_INSTANCE_CHECK", true);

require_once("../../server/bootstrap.php");


class InstanciasControllerTest extends PHPUnit_Framework_TestCase {
	
	private static $instance_id = 66;


	public function testNuevaInstancia( ){
		
		if(!is_null(self::$instance_id)) return;
		
		$id = InstanciasController::Nueva(null, "instacia para unit testing" );
		
		$this->assertInternalType('int', $id);
		
		self::$instance_id = $id;
		
	}



	public function testBuscarInstancia( ){

		//debe existir la instancia cuyo id es $instance_id
		$this->assertFalse( is_null( $i = InstanciasController::BuscarPorId( self::$instance_id ) ) );
		$this->assertFalse( is_null( InstanciasController::BuscarPorToken( $i["instance_token"] ) ) );		
		
	}	

}