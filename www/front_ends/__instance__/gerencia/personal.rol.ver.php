<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );

		//
		// Parametros necesarios
		// 
		$page->requireParam(  "rid", "GET", "Este rol no existe." );
		$este_rol = RolDAO::getByPK( $_GET["rid"] );
		
                
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_rol->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este rol", "personal.editar.rol.php?rid=".$_GET["rid"]);
                
                $btn_eliminar = new MenuItem("Eliminar este rol", null);
                $btn_eliminar->addApiCall("api/personal/rol/eliminar");
                $btn_eliminar->onApiCallSuccessRedirect("personal.lista.rol.php");
                $btn_eliminar->addName("eliminar");
                
                $funcion_eliminar = " function eliminar_rol(btn){".
                            "if(btn == 'yes')".
                            "{".
                                "var p = {};".
                                "p.id_rol = ".$_GET["rid"].";".
                                "sendToApi_eliminar(p);".
                            "}".
                        "}".
                        "      ".
                        "function confirmar(){".
                        " Ext.MessageBox.confirm('Eliminar', 'Desea eliminar este rol?', eliminar_rol );".
                        "}";
                
                $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);
                
                $menu->addMenuItem($btn_eliminar);
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_rol );
                $form->setEditable(false);
		$form->hideField( array( 
				"id_rol",
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
//	    $form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::search( new Unidad( array( "activa" => 1 ) ) ));
		$page->addComponent( $form );
		
		$page->render();
