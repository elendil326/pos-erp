<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                $page->requireParam("tid", "GET", "Ese tipo de almacen no existe");
                $este_tipo_almacen = TipoAlmacenDAO::getByPK($_GET["tid"]);
                
                //titulos
                $page->addComponent( new TitleComponent( "Editar tipo de almacen ".$_GET["tid"] ) );

                //forma de nuevo almacen
                $form = new DAOFormComponent( $este_tipo_almacen ) ;

                $form->hideField( array( 
                                "id_tipo_almacen"
                         ));
                $form->sendHidden("id_tipo_almacen");
                
                $form->addApiCall( "api/almacen/tipo/editar" , "POST");
                $form->onApiCallSuccessRedirect("sucursales.lista.tipo_almacen.php");

                

                $page->addComponent( $form );


	//render the page
		$page->render();
