<?php

	require_once("../../../server/bootstrap.php");

	$page = new StdComponentPage();

	$page->addComponent( new LoginComponent() );

	$page->render();

