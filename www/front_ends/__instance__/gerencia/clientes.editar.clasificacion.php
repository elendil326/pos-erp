<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta clasificacion de cliente no existe." );
		$esta_clasificacion = ClasificacionClienteDAO::getByPK( $_GET["cid"] );
                //titulos
                $page->addComponent( new TitleComponent( "Editar clasificacion de cliente: ".$esta_clasificacion->getNombre() ) );

                //forma de nuevo paquete
                $form = new DAOFormComponent( $esta_clasificacion );

                $form->hideField( array( 
                                "id_clasificacion_cliente"
                         ));
                $form->sendHidden("id_clasificacion_cliente");


                $form->addApiCall( "api/cliente/clasificacion/editar/" );
                $form->onApiCallSuccessRedirect("clientes.lista.clasificacion.php");

                $page->addComponent( $form );


                //render the page
                
		$page->render();
