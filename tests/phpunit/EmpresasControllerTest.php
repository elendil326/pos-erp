<?php

date_default_timezone_set ( "America/Mexico_City" );

if(!defined("BYPASS_INSTANCE_CHECK"))
	define("BYPASS_INSTANCE_CHECK", false);

$_GET["_instance_"] = 71;

require_once("../../server/bootstrap.php");





class EmpresasControllerTest extends PHPUnit_Framework_TestCase {
	
	
	private $_empresa;

	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}
	

	
	public function testBootstrap(){
		
		$r = SesionController::Iniciar(123, 1, true);

	 	$this->assertEquals($r["login_succesful"], true);
	
		$r = SesionController::getCurrentUser(  );
		
		$this->assertEquals($r->getIdUsuario(), 1);
	}

	public function testNuevo(){

		$direccion = Array(
			"calle"  			=> "Monte Balcanes",
	        "numero_exterior"   => "107",
	        "colonia"  			=> "Arboledas",
	        "id_ciudad"  		=> 334,
	        "codigo_postal"  	=> "38060",
	        "numero_interior"  	=> null,
	        "texto_extra"  		=> "Calle cerrada",
	        "telefono1"  		=> "4616149974",
	        "telefono2"			=> "45*451*454"
		);
		
		$id_moneda = 1;
		$razon_social = "Caffeina Software";
		$rfc  = "GOHA8801317";
		

		
		$empresas_con_rfc = EmpresaDAO::getByRFC($rfc);
		
		foreach ($empresas_con_rfc as $empObj) {
			EmpresasController::Eliminar( $empObj->getIdEmpresa() );
		}


		$this->_empresa = EmpresasController::Nuevo(
				$direccion, 
				$id_moneda, 
				$razon_social, 
				$rfc
			);

		$this->assertInternalType('int', $this->_empresa["id_empresa"] );
	}



	
	public function testBuscar(){
		try{
			$busqueda = EmpresasController::Buscar();			
			
		}catch(Exception $e){
			Logger::testLog($e);
			
		}


		
		//debe haber por lo menos un resultado
		if( $busqueda["numero_de_resultados"] == 0 ){
			echo "REVISAR BUG EN DAOS. serch() debe regresar getAll() cuando no se envian parametros";
		}
		$this->assertGreaterThan( 0, $busqueda["numero_de_resultados"]);
		$this->assertInternalType('int', $busqueda["numero_de_resultados"]);
		$this->assertEquals( $busqueda["numero_de_resultados"], sizeof( $busqueda["resultados"]));
	}
	


	
	public function testBuscarWithParams(){
		$busqueda = EmpresasController::Buscar( true );
		//solo debe haber empresas activas
		$res = $busqueda["resultados"];
		
		foreach ($res as $emp) {
			$this->assertEquals(1, $emp->getActivo());
		}
		
	}
	

}
