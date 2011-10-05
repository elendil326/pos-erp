<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");


	$c = new EmpresasController();

	$c->lista();

	
?>