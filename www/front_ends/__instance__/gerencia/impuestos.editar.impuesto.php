<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "iid", "GET", "El impuesto no existe." );
		$este_impuesto = ImpuestoDAO::getByPK( $_GET["iid"] );
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar impuesto " . $este_impuesto->getNombre() , 2 ));

		$form = new DAOFormComponent( $este_impuesto );

		$form->hideField( array( 
				"id_impuesto",
			 ));
                $form->sendHidden("id_impuesto");
                
                $form->createComboBoxJoin("es_monto", "es_monto", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "No" ) ), 
                        $este_impuesto->getEsMonto());

		$form->addApiCall( "api/impuestos_retenciones/impuesto/editar" , "GET");
                $form->onApiCallSuccessRedirect("impuestos.lista.impuesto.php");
		

		$page->addComponent( $form );
                
		$page->render();
