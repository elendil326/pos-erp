<?php

	define("BYPASS_INSTANCE_CHECK", true);

	require_once("../../../server/bootstrap.php");


	$p = new JediComponentPage( );
	$p->addComponent( new TitleComponent( "Instancias" ) );


	/**
	  *
	  * Lista de instancias
	  *
	  **/
	$p->addComponent( new TitleComponent( "Instancias instaladas", 2 ) );


	$headers = array( 	"instance_id" => "Instance ID",
	 					"descripcion" => "Descripcion",
						"instance_token" => "Token");
	
	$t = new TableComponent( $headers , InstanciasController::Buscar());
	$t->addOnClick( "instance_id" , "(function(i){window.location='instancias.ver.php?id='+i;})"  );
	$p->addComponent( $t );	


	$p->render( );






