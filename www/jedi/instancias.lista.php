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
	$p->addComponent( new TitleComponent( "Instancias instaladas", 3 ) );

	$headers = array( 							
						"instance_id" => "instnace_id",
						"instance_token" => "instance_token",
						"fecha_creacion" => "Creada",
	 					"descripcion" => "Descripcion"
					);
	
	$t = new TableComponent( $headers , InstanciasController::Buscar());

	$t->addColRender( "fecha_creacion", "FormatTime" );	

	$t->addOnClick( "instance_id" , "(function(i){window.location='instancias.ver.php?id='+i;})"  );

	$p->addComponent( $t );	



	$p->render( );






