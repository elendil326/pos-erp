<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	
	$page->addComponent( new TitleComponent( "Nueva Empresa" ) );



	
	$form = new DAOFormComponent( array ( new Empresa() , new Direccion ) );
	$form->addOnClick( "Nueva empresa", "empresa.nueva()" );
	$page->addComponent( $form );
	
	$page->render();





