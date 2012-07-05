<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Parametros necesarios
	// 
	$page->requireParam("vid", "GET", "Esta venta/cotizacion no existe.");

	try{
		ImpresionesController::Venta($_GET["vid"]);
	}catch(InvalidDataException $e){
		$page->addComponent($e->getMessage());
		$page->render();
	}
