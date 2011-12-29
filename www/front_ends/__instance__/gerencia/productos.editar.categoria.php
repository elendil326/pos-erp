<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta categoria de producto no existe." );
		$esta_categoria = ClasificacionProductoDAO::getByPK( $_GET["cid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar clasificacion de producto " . $esta_categoria->getNombre()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $esta_categoria );
		$form->hideField( array( 
                                "id_clasificacion_producto",
                                "activa",
                                
			 ));
                
                $form->renameField(array( "id_clasificacion_producto" => "id_categoria" ));
                
                $form->sendHidden("id_categoria");
                
                $form->addApiCall( "api/producto/categoria/editar/", "GET" );
                
                $form->onApiCallSuccessRedirect("productos.lista.categoria.php");
                
		$page->addComponent( $form );
                
		$page->render();
