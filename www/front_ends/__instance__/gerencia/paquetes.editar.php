<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                 //
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este paquete no existe." );
		$este_paquete = PaqueteDAO::getByPK( $_GET["pid"] );
                //titulos
	$page->addComponent( new TitleComponent( "Editar paquete" ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( $este_paquete );
	
	$form->hideField( array( 
			"id_paquete",
                        "activo"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/paquete/editar/", "GET" );
	
//	$form->makeObligatory(array( 
//			"nombre"
//		));
	
//	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
