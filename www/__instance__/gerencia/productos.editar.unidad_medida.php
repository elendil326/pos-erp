<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "umid", "GET", "Esta unidad no existe." );
		$esta_unidad = UnidadMedidaDAO::getByPK( $_GET["umid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar unidad medida " . $esta_unidad->getAbreviacion()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $esta_unidad );

		$form->createComboBoxJoin("id_categoria_unidad_medida", "descripcion", CategoriaUnidadMedidaDAO::getAll());
	    
		$form->createComboBoxJoin("tipo_unidad_medida", "tipo_unidad_medida", array( "Referencia UdM para esta categoria", "Mayor que la UdM de referencia", "Menor que la UdM de referencia"));

		$form->hideField( array( 
                                "id_unidad_medida",
                                "activa"
			 ));
                $form->sendHidden("id_unidad_medida");
				$form->sendHidden("activa");
                
                $form->addApiCall( "api/producto/udm/unidad/editar/", "POST" );
                $form->onApiCallSuccessRedirect("productos.lista.unidad_medida.php");
                
        $form->makeObligatory(array( 			
			"id_categoria_unidad_medida",
			"tipo_unidad_medida"
		));
  
		$page->addComponent( $form );
                
		$page->render();
