<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		//
		// Requerir parametros
		// 
		$page->requireParam(  "eid", "GET", "Esta empresa no existe." );
		$esta_empresa = EmpresaDAO::getByPK( $_GET["eid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $esta_empresa->getRazonSocial() , 2 ));
		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar esta empresa", "empresas.editar.php?eid=".$_GET["eid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Desactivar esta empresa", null);
                $btn_eliminar->addApiCall("api/empresa/eliminar", "POST");
                $btn_eliminar->onApiCallSuccessRedirect("empresas.lista.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_empresa(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_empresa = ".$_GET["eid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar esta empresa?', eliminar_empresa );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $esta_empresa );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_empresa",
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
