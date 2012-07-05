<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //
		// Parametros necesarios
		// 
		$page->requireParam(  "rid", "GET", "Este rol no existe." );
		$este_rol = RolDAO::getByPK( $_GET["rid"] );
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar rol de " . $este_rol->getNombre() , 2 ));

		//
		// Forma de rol
		// 
		$form = new DAOFormComponent( $este_rol );
		$form->hideField( array( 
				"id_rol",
			 ));
                $form->sendHidden("id_rol");
                
                
                $form->addApiCall( "api/personal/rol/editar/" );
                $form->onApiCallSuccessRedirect("personal.lista.rol.php");
                
		$page->addComponent( $form );
                
		$page->render();
