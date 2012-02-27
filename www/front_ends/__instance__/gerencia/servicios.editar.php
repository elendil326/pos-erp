<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

        //
		// Parametros necesarios
		// 
		$page->requireParam(  "sid", "GET", "Este servicio no existe." );
		$este_servicio = ServicioDAO::getByPK( $_GET["sid"] );
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar servicio " . $este_servicio->getNombreServicio()  , 2 ));

		//
		// Forma de edicion
		// 
		$form = new DAOFormComponent( $este_servicio );
		$form->hideField( array( 
                   "id_servicio",
                   "activo"                
			 	));
			
        $form->sendHidden("id_servicio");
        $form->addApiCall( "api/servicios/editar/", "GET" );
        $form->onApiCallSuccessRedirect("servicios.ver.php?sid=".$_GET["sid"]);

		$page->addComponent( $form );
                
		$page->render();
