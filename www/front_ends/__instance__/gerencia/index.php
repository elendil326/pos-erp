<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	
	$page->addComponent( new TitleComponent( "Bienvenido" ) );
	$page->addComponent( new TitleComponent( "POS ERP", 2 ) );

	/*$big_menu = new BigMenuComponent();
	$big_menu->addItem(  );*/
	
	$page->render();





