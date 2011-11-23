<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva Unidad" ) );

	//forma de nueva unidad
	$form = new DAOFormComponent( array( new unidad() ) );
	
	$form->hideField( array( 
			"id_unidad",
                        "activa"
		 ));

//
//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/producto/unidad/nueva/" , "GET");
	
	$form->makeObligatory(array( 
			"nombre",
                        "es_entero"
		));
	
	
	
	$page->addComponent( $form );
        
        //render the page
		$page->render();
