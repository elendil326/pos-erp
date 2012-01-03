<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                 //
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este paquete no existe." );
		$este_paquete = PaqueteDAO::getByPK( $_GET["pid"] );
                //titulos
                $page->addComponent( new TitleComponent( "Editar paquete" ) );

                //forma de nuevo paquete
                $form = new DAOFormComponent( $este_paquete );

                $form->hideField( array( 
                                "id_paquete",
                                "activo"
                         ));
                $form->sendHidden("id_paquete");

                $form->addApiCall( "api/paquete/editar/", "GET" );
                $form->onApiCallSuccessRedirect("paquetes.lista.php");


                $page->addComponent( $form );


                //render the page
		$page->render();
