<?php

	define("BYPASS_INSTANCE_CHECK", true);


	require_once("../../server/bootstrap.php");
	
	
	

	
	/***
	 * 
	 *  Page Rendering
	 * 
	 * 
	 * */
	$p = new JediComponentPage( "Detalles de la instancia" );
	
	$p->requireParam( "id", "GET", "Esta instancia no existe.", "InstanciasController::BuscarPorId" );
	
	
	$instancia = InstanciasController::BuscarPorId( $_GET["id"] );

	/**
	  *
	  *	Nueva instancia
	  *
	  **/
	$p->addComponent( new TitleComponent( "Detalles de la instancia" ) );
	
	
	$p->addComponent( new TitleComponent( $instancia["instance_token"], 3 ) );	
	
	
	
	$p->addComponent( "<br><div class='POS Boton'><a href='../". $instancia["instance_token"] ."/' target='_new'>Visitar la instancia</a></div>");
	

	
	$p->render( );
	

