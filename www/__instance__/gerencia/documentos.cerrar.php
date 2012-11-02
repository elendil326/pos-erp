<?php
	
	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");



	$movimiento = VentaDAO::getByPK( 354 )->asArray();;

	$documento = DocumentoBaseDAO::getByPK( 51 );

//	var_dump($movimiento);

	DocumentosController::Cerrar( 51, $movimiento );

	//buscar movimiento existente


	//buscar documento a realizar


	//validar documento


	//validar que los parametros del documento esten disponibles


	//insertar en documento tabla

	//mandar imprimir