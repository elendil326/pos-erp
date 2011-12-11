<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta clasificacion de proveedor no existe." );
		$esta_clasificacion = ClasificacionProveedorDAO::getByPK( $_GET["cid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_clasificacion->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta clasificacion", "proveedores.editar.clasificacion.php?cid=".$_GET["cid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar esta clasificacion de proveedor", null);
                $btn_eliminar->addApiCall("api/proveedor/clasificacion/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("proveedores.lista.clasificacion.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_clasificacion(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_clasificacion_proveedor = ".$_GET["cid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta clasificacion de proveedor?', eliminar_clasificacion );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_clasificacion );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_clasificacion_proveedor",
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
