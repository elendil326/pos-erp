<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		//forma de nuevo cliente
		$form = new DAOFormComponent( new Impuesto() );

		$form->hideField( array( 
				"id_impuesto",
			 ));


		$form->addApiCall( "api/impuestos_retenciones/impuesto/nuevo", "GET" );
		
		$form->makeObligatory( array(
			"nombre",
			"monto_porcentaje",
			"es_monto"			
		));


//		$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//		$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );

		$page->addComponent( $form );
		$page->render();
