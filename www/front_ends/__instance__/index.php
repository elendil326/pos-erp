<?php

	require_once("../../../server/bootstrap.php");

	if(isset($_GET["cs"]) && ($_GET["cs"] == 1)){
		SesionController::Cerrar();
	}

	//antes de crear el componente de login,
	//vamos a revisar si el usuario tiene una
	//sesion iniciada, y lo adentramos
	if(SesionController::isLoggedIn()){
		die(header( "Location: g/" ));
	}

	$page = new PosComponentPage();
	$login = new LoginComponent();
	$login->setLoginApiCall("api/sesion/iniciar/");
	$page->addComponent( $login );
	$page->render();

