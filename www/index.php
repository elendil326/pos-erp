<?php


	define("BYPASS_INSTANCE_CHECK", true);
	
	require_once("../server/bootstrap.php");
	
	$page = new PosComponentPage();

	$page->render();