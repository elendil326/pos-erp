<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este paquete no existe." );
		$este_paquete = PaqueteDAO::getByPK( $_GET["pid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_paquete->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este paquete", "paquetes.editar.php?pid=".$_GET["pid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar este paquete", null);
                $btn_eliminar->addApiCall("api/paquete/eliminar", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("paquetes.lista.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_paquete(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_paquete = ".$_GET["pid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar_desactivacion(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este paquete?', eliminar_paquete );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar_desactivacion", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
                $btn_activar = new MenuItem("Activar este paquete", null);
                $btn_activar->addApiCall("api/paquete/activar", "GET");
                $btn_activar->onApiCallSuccessRedirect("paquetes.lista.php");
                $btn_activar->addName("activar");
                
                $funcion_activar = " function activar_paquete(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_paquete = ".$_GET["pid"].";".
                                "sendToApi_activar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar_activacion(){".
                        " Ext.MessageBox.confirm('Activar', 'Desea activar este paquete?', activar_paquete );".
                        "}";
                
                $btn_activar->addOnClick("confirmar_activacion", $funcion_activar);
                
                $menu->addMenuItem($btn_activar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_paquete );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_paquete",
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
