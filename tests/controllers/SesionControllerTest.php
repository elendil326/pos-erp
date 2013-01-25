<?php

require_once("../../server/bootstrap.php");

class SesionControllerTests extends PHPUnit_Framework_TestCase {

	protected function setUp(){

	}

	public function testDeleteAuthTokenOnLogout(){

		//insert a new user
		$sql = "Delete from `usuario` where `codigo_usuario` = 'foo';";
		global $POS_CONFIG;
		$POS_CONFIG["INSTANCE_CONN"]->Execute( $sql );

		$r = PersonalYagentesController::NuevoUsuario( "foo", "1", "name", "password444222");

		$r = SesionController::Iniciar( "password444222", "foo"  );

		//this token should be non existing when i log out
		$auth_token = $r["auth_token"];

		$vos = SesionDAO::search( new Sesion( array( "auth_token" => $auth_token ) )  );

		$r = SesionController::Cerrar( $auth_token );

		$vos = SesionDAO::search( new Sesion( array( "auth_token" => $auth_token ) )  );

		$this->assertEquals( sizeof($vos), 0  );
	}
	

}

