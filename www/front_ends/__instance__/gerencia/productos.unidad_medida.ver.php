<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "umid", "GET", "Esta unidad de medida no existe." );
		$esta_unidad = UnidadMedidaDAO::getByPK( $_GET["umid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_unidad->getAbreviacion() , 2 ));

		
		//
		// Menu de opciones
		//
                if($esta_unidad->getActiva())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Editar esta unidad de medida", "productos.editar.unidad_medida.php?umid=".$_GET["umid"]);
                    //$menu->addItem("Desactivar este producto", null);

                    $btn_eliminar = new MenuItem("Desactivar esta unidad de medida", null);
                    $btn_eliminar->addApiCall("api/producto/unidad/eliminar", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("productos.lista.unidad_medida.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_unidad(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_unidad_medida_convertible = ".$_GET["umid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta unidad de medida?', eliminar_unidad );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

                    $menu->addMenuItem($btn_eliminar);

                    $page->addComponent( $menu);
                }
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_unidad );
		$form->setEditable(false);
		
		$form->createComboBoxJoin("id_categoria_unidad_medida", "descripcion", CategoriaUnidadMedidaDAO::getAll(), $esta_unidad->getIdCategoriaUnidadMedida());
		
		$form->hideField( array( 
				"id_unidad_medida",
			 ));

		$page->addComponent( $form );
		
		$page->render();
