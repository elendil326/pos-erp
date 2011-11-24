<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );
		
		$page->requireParam(  "pid", "GET", "Este producto no existe." );
		
		
		
		$page->render();
