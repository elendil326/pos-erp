<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "bid", "GET", "Este billete no existe." );
		$este_billete = BilleteDAO::getByPK( $_GET["bid"] );
                //titulos
	$page->addComponent( new TitleComponent( "Editar Billete: ".$este_billete->getNombre() ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( $este_billete );
	
	$form->hideField( array( 
			"id_billete"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/efectivo/billete/editar/", "GET" );
	
//	$form->makeObligatory(array( 
//			"nombre"
//		));
	
//	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
                
		$page->render();
