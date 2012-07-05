<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Este producto no existe." );
		$esta_categoria = ClasificacionProductoDAO::getByPK( $_GET["cid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_categoria->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta categoria", "productos.editar.categoria.php?cid=".$_GET["cid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar esta categoria", null);
                $btn_eliminar->addApiCall("api/producto/categoria/desactivar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("productos.lista.categoria.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_categoria(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_categoria = ".$_GET["cid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta categoria de producto?', eliminar_categoria );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_categoria );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_clasificacion_producto",
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
	    $form->createComboBoxJoin("id_categoria_padre", "nombre", ClasificacionProductoDAO::getAll(), $esta_categoria->getIdClasificacionProducto() );

		$page->addComponent( $form );
		
		$page->render();
