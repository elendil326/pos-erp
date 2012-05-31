<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$form = new DAOFormComponent( new Impuesto() );

		$form->hideField( array( 
				"id_impuesto",
			 ));


		$form->addApiCall( "api/impuesto/nuevo" );
                $form->onApiCallSuccessRedirect("impuestos.lista.impuesto.php");
		
		$form->makeObligatory( array(
			"nombre",
			"monto_porcentaje",
			"es_monto"			
		));
                
                $form->createComboBoxJoin("es_monto", "es_monto", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "No" ) ));

		$page->addComponent( $form );
		$page->render();
