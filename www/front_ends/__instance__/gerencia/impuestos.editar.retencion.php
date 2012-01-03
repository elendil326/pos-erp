<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "rid", "GET", "La retencion no existe." );
		$esta_retencion = RetencionDAO::getByPK( $_GET["rid"] );
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar retencion " . $esta_retencion->getNombre() , 2 ));
                
                $form = new DAOFormComponent( $esta_retencion );

		$form->hideField( array( 
				"id_retencion",
			 ));
                $form->sendHidden("id_retencion");
                
                $form->createComboBoxJoin("es_monto", "es_monto", array( array( "id" => 1, "caption" => "Si" ), array( "id" => 0, "caption" => "No" ) ),
                        $esta_retencion->getEsMonto());

		$form->addApiCall( "api/impuestos_retenciones/retencion/editar" , "GET");
                $form->onApiCallSuccessRedirect("impuestos.lista.retencion.php");
		

		$page->addComponent( $form );
                
		$page->render();
