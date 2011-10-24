<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	
	$page->addComponent( new TitleComponent( "Nuevo cliente" ) );
	
	
	$foo = new Usuario();
	$cmp = new DAOFormComponent( $foo );
	$page->addComponent( $cmp );

	
	$page->render();





