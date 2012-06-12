<?php

	if(!class_exists("PHPUnit_Runner_Version")){
		define("BYPASS_INSTANCE_CHECK", false);
		require_once("../../../../server/bootstrap.php");
	}
	
	
	
	$page = new ClienteComponentPage();
	$banner = new BannerComponent("POS ERP", "Bienvenido a POS ERP <br>un sistema de gestion empresarial", "../../../media/EAbydW1M_XR.png");
	$page->addComponent($banner);
	$page->render();





