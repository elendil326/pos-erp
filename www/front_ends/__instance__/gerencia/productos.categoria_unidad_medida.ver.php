<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cuid", "GET", "Esta categoria unidad de medida no existe." );
		$esta_unidad = CategoriaUnidadMedidaDAO::getByPK( $_GET["cuid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_unidad->getDescripcion() , 2 ));

		
		//
		// Menu de opciones
		//
                if($esta_unidad->getActiva())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Editar esta categoria unidad de medida", "productos.editar.categoria_unidad_medida.php?cuid=".$_GET["cuid"]);
                    //$menu->addItem("Desactivar este producto", null);

                    $btn_eliminar = new MenuItem("Desactivar esta categoria unidad de medida", null);
                    $btn_eliminar->addApiCall("api/producto/unidad/eliminar", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("productos.lista.categoria_unidad_medida.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_unidad(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_unidad_medida_convertible = ".$_GET["cuid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta categoria unidad de medida?', eliminar_unidad );".
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
		
		$form->hideField( array( 
				"id_categoria_unidad_medida",
			 ));

		$page->addComponent( $form );
		
		$page->render();
