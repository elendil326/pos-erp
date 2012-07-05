<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "tid", "GET", "Este traspaso no existe." );
		$este_traspaso = TraspasoDAO::getByPK( $_GET["tid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de traspaso del almacen " . AlmacenDAO::getByPK($este_traspaso->getIdAlmacenEnvia())->getNombre()
                        ." al almacen ". AlmacenDAO::getByPK($este_traspaso->getIdAlmacenRecibe())->getNombre(), 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este traspaso", "sucursales.editar.traspaso.almacen.php?tid=".$_GET["tid"]);
		//$menu->addItem("Desactivar este producto", null);
                
                $btn_eliminar = new MenuItem("Cancelar este traspaso", null);
                $btn_eliminar->addApiCall("api/sucursal/almacen/traspaso/cancelar/", "GET");
                $btn_eliminar->onApiCallSuccessRedirect("sucursales.lista.traspaso.almacen.php");
                $btn_eliminar->addName("cancelar");
                
                $funcion_eliminar = " function cancelar_traspaso(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_traspaso = ".$_GET["tid"].";".
                                "sendToApi_cancelar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar_cancelacion(){".
                        " Ext.MessageBox.confirm('Cancelar', 'Desea cancelar este traspaso?', cancelar_traspaso );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar_cancelacion", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
                $btn_enviar = new MenuItem("Enviar este traspaso", null);
                $btn_enviar->addApiCall("api/sucursal/almacen/traspaso/enviar/", "GET");
                $btn_enviar->onApiCallSuccessRedirect("sucursales.lista.traspaso.almacen.php");
                $btn_enviar->addName("enviar");
                
                $funcion_enviar = " function enviar_traspaso(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_traspaso = ".$_GET["tid"].";".
                                "sendToApi_enviar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar_envio(){".
                        " Ext.MessageBox.confirm('Enviar', 'Desea enviar este traspaso?', enviar_traspaso );".
                        "}";
                
                $btn_enviar->addOnClick("confirmar_envio", $funcion_enviar);
                
                $menu->addMenuItem($btn_enviar);
                
                $btn_recibir = new MenuItem("Recibir este traspaso", null);
                $btn_recibir->addApiCall("api/sucursal/almacen/traspaso/recibir/", "GET");
                $btn_recibir->onApiCallSuccessRedirect("sucursales.lista.traspaso.almacen.php");
                $btn_recibir->addName("recibir");
                
                $funcion_recibir = " function recibir_traspaso(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_traspaso = ".$_GET["tid"].";".
                                "sendToApi_recibir(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar_recibo(){".
                        " Ext.MessageBox.confirm('Recibir', 'Desea recibir este traspaso?', recibir_traspaso );".
                        "}";
                
                $btn_recibir->addOnClick("confirmar_recibo", $funcion_recibir);
                
                $menu->addMenuItem($btn_recibir);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_traspaso );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_traspaso",
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
                
                $form->createComboBoxJoinDistintName("id_almacen_recibe","id_almacen", "nombre", AlmacenDAO::getAll(), $este_traspaso->getIdAlmacenRecibe() );
	$form->createComboBoxJoinDistintName("id_almacen_envia","id_almacen", "nombre", AlmacenDAO::getAll(), $este_traspaso->getIdAlmacenEnvia() );
        
        $form->createComboBoxJoinDistintName("id_usuario_recibe","id_usuario", "nombre", UsuarioDAO::getAll(), $este_traspaso->getIdUsuarioRecibe() );
	$form->createComboBoxJoinDistintName("id_usuario_envia","id_usuario", "nombre", UsuarioDAO::getAll(), $este_traspaso->getIdUsuarioEnvia() );
        
        $form->createComboBoxJoinDistintName("id_usuario_programa","id_usuario", "nombre", UsuarioDAO::getAll(), $este_traspaso->getIdUsuarioPrograma() );
                
		$page->addComponent( $form );
		
		$page->render();
