<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();
                
	//titulos
	$page->addComponent( new TitleComponent( "Nueva clasificacion de servicio" ) );

	//forma de nueva clasificacion de servicio
	$form = new DAOFormComponent( array( new ClasificacionServicio() ) );
	
	$form->hideField( array( 
			"id_clasificacion_servicio"
		 ));

	$form->addApiCall( "api/servicios/clasificacion/nueva/", "GET" );
	
	$form->makeObligatory(array( 
			"nombre"
		));
	
	
	$page->addComponent( $form );

	//render the page
	$page->render();
