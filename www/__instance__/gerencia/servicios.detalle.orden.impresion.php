<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Parametros necesarios
	// 
	$page->requireParam("oid", "GET", "Esta orden de servicio no existe.");


	ImpresionesController::OrdenDeServicio($_GET["oid"]);