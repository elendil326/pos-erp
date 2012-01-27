<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();


	$banner = new BannerComponent("POS ERP", "Bienvenido", "../../../media/EAbydW1M_XR.png");
	
	
	
	$page->addComponent( $banner );
	
	$page->render();





