<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "uid", "GET", "Esta unidad no existe." );
		$esta_unidad = UnidadDAO::getByPK( $_GET["uid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_unidad->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta unidad", "productos.editar.unidad.php?uid=".$_GET["uid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar esta unidad", null);
                $btn_eliminar->addApiCall("api/producto/unidad/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("productos.lista.unidad.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_unidad(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_unidad_convertible = ".$_GET["uid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta unidad?', eliminar_unidad );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_unidad );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_unidad",
			 ));
//		$form->makeObligatory(array( 
//				"compra_en_mostrador",
//				"costo_estandar",
//				"nombre_producto",
//				"id_empresas",
//				"codigo_producto",
//				"metodo_costeo",
//				"activo"
//			));
//	    $form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::getAll(), $este_producto->getIdUnidad() );
		$page->addComponent( $form );
		
		$page->render();
