<?php

	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../../server/bootstrap.php");
	
	
	

	
	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$p = new JediComponentPage( "Detalles de la instancia" );
	$p->requireParam( "id", "GET", "Esta instancia no existe.", "InstanciasController::BuscarPorId" );
	
	
	

	/**
	  *
	  *	Nueva instancia
	  *
	  **/
	$p->addComponent( new TitleComponent( "Detalles de la instancia" ) );
	


	$p->render( );
