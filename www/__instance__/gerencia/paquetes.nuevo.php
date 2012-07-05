<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo paquete" ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( array( new Paquete() ) );
	
	$form->hideField( array( 
			"id_paquete",
                        "activo"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/paquete/nuevo/", "GET" );
	
	$form->makeObligatory(array( 
			"nombre"
		));
	
//	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
