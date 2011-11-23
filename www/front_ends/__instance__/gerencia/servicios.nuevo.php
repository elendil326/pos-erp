<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo servicio" ) );

	//forma de nuevo servicio
	$form = new DAOFormComponent( array( new Servicio() ) );
	
	$form->hideField( array( 
			"id_servicio"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/servicios/nuevo/", "GET" );
	
	$form->makeObligatory(array( 
			"costo_estandar",
                        "metodo_costeo",
                        "nombre_servicio",
                        "codigo_servicio",
                        "empresas",
                        "compra_en_mostrador"
		));
	
	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
