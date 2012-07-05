<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "uid", "GET", "Esta unidad no existe." );
		$esta_unidad = UnidadDAO::getByPK( $_GET["uid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar unidad " . $esta_unidad->getNombre()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $esta_unidad );
		$form->hideField( array( 
                                "id_unidad",
                                "activa"
			 ));
                $form->sendHidden("id_unidad");
                
                $form->addApiCall( "api/producto/unidad/editar/", "GET" );
                $form->onApiCallSuccessRedirect("productos.lista.unidad.php");
                
                
		$page->addComponent( $form );
                
		$page->render();
