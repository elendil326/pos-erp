<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
	
	$page->addComponent( new TitleComponent( "Nuevo cliente" ) );
	



	$page->addComponent( new TitleComponent( "Datos personales" , 3 ) );

	$form = new DAOFormComponent( array( new Usuario(), new Direccion() ) );


	$form->addOnClick( "Crear el nuevo cliente" , "cliente.nuevo()" );

	$page->addComponent( $form );


	
	$html = new FreeHtmlComponent("");

	$page->addComponent( $html );

	$page->render();





