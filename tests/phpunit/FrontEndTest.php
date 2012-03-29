<?php

require_once("../../server/bootstrap.php");


class FrontEndTest extends PHPUnit_Framework_TestCase {
	
	protected function setUp(){
		Logger::log("-----------------------------");
		$r = SesionController::Iniciar(123, 1, true);
	}


	public function testFrontEnd(){
		ob_start();
		
		require_once("../../www/index.php");
		include '../../www/front_ends/__instance__/gerencia/index.php';	
		ob_get_contents();
		ob_end_clean();
	}
	
}


