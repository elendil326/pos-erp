<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();


	$banner = new BannerComponent("POR ERP", "Bienvenido", "https://s-static.ak.facebook.com/rsrc.php/v1/y4/r/EAbydW1M_XR.png");
	
	
	
	$page->addComponent( $banner );
	
	$page->render();





