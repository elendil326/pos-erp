<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este producto no existe." );
		$este_producto = ProductoDAO::getByPK( $_GET["pid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar producto " . $este_producto->getNombreProducto()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $este_producto );
		$form->hideField( array( 
                                "id_producto",
                                "activo",
                                
			 ));
			
		$form->sendHidden("id_producto");
                
                $form->addApiCall( "api/producto/editar/", "GET" );
                
                $form->onApiCallSuccessRedirect("productos.lista.php");
                
                $form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::search(new Unidad( array( "activa" => 1 ) )), $este_producto->getIdUnidad() );
                
		$page->addComponent( $form );
                
		$page->render();
