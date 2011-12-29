<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta categoria de servicio no existe." );
		$esta_categoria = ClasificacionServicioDAO::getByPK( $_GET["cid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar clasificacion de servicio " . $esta_categoria->getNombre()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $esta_categoria );
		$form->hideField( array( 
                                "id_clasificacion_servicio",
                                "activa"
                                
			 ));
                $form->sendHidden("id_clasificacion_servicio");
                
                $form->addApiCall( "api/servicios/clasificacion/editar/", "GET" );
                $form->onApiCallSuccessRedirect("servicios.lista.clasificacion.php");
                
		$page->addComponent( $form );
                
		$page->render();
