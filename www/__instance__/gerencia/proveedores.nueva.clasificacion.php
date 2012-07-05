<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //titulos
	$page->addComponent( new TitleComponent( "Nueva clasificacion de proveedor" ) );

	//forma de nueva clasificacion de proveedor
	$form = new DAOFormComponent( array( new ClasificacionProveedor() ) );
	
	$form->hideField( array( 
			"id_clasificacion_proveedor",
                        "activa"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/proveedor/clasificacion/nueva/", "GET" );
	
	$form->makeObligatory(array( 
			"nombre"
		));
	
//	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
