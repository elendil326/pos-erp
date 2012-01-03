<?php

	require_once("../../../server/bootstrap.php");

	$page = new StdComponentPage();
	$login = new LoginComponent();
	$login->setLoginApiCall("api/sesion/iniciar/");
	$page->addComponent( $login  );

	$page->render();

