<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$form = new DAOFormComponent( new Impuesto() );

		$form->hideField( array( 
				"id_impuesto",
			 ));


		$form->addApiCall( "api/impuesto/nuevo" );
                $form->onApiCallSuccessRedirect("impuestos.php");

		//$form->addField("codigo", "codigo", "text");
		
		$form->makeObligatory( array(
			"nombre",
			"codigo",
			"importe",
			"es_monto"			
		));
                
        $form->createComboBoxJoin("es_monto", "es_monto", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "No" ) ));
        $form->createComboBoxJoin("aplica","aplica",array(array("id" => 0, "caption" => "Compra"),array("id" => 1, "caption" => "Venta"), array("id" => 2, "caption" => "Todas")));