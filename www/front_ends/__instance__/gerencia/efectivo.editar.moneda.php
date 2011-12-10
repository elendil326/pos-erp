<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "mid", "GET", "Esta moneda no existe." );
		$esta_moneda = MonedaDAO::getByPK( $_GET["mid"] );
                //titulos
	$page->addComponent( new TitleComponent( "Editar Moneda: ".$esta_moneda->getNombre() ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( $esta_moneda );
	
	$form->hideField( array( 
			"id_moneda"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/efectivo/moneda/editar/", "GET" );
	
//	$form->makeObligatory(array( 
//			"nombre"
//		));
	
//	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
                
		$page->render();
