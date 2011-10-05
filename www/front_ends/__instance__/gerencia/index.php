<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	$c = new EmpresasController();
	$c->Lista(1);
	$page->render();





