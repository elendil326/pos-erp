<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cuid", "GET", "Esta categoria unidad medida no existe." );
		$esta_cat = CategoriaUnidadMedidaDAO::getByPK( $_GET["cuid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar unidad medida " . $esta_cat->getDescripcion()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $esta_cat );

		$form->hideField( array( 
                                "id_categoria_unidad_medida",
                                "activa"
			 ));
                $form->sendHidden("id_categoria_unidad_medida");
				$form->sendHidden("activa");
             
                $form->addApiCall( "api/producto/udm/categoria/editar", "POST" );
                $form->onApiCallSuccessRedirect("productos.lista.categoria_unidad_medida.php");
                
        
  
		$page->addComponent( $form );
                
		$page->render();
