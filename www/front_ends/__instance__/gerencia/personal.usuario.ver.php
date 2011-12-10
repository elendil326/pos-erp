<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "uid", "GET", "Este usuario no existe." );
		$este_usuario = UsuarioDAO::getByPK( $_GET["uid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_usuario->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este usuario", "personal.editar.usuario.php?uid=".$_GET["uid"]);
                
                $btn_eliminar = new MenuItem("Desactivar este usuario", null);
                $btn_eliminar->addApiCall("api/personal/usuario/eliminar");
                $btn_eliminar->onApiCallSuccessRedirect("personal.lista.usuario.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_usuario(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_usuario = ".$_GET["uid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este usuario?', eliminar_usuario );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_usuario );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_usuario",
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
