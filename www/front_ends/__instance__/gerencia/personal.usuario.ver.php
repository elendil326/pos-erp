<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "uid", "GET", "Este usuario no existe." );
		$este_usuario = UsuarioDAO::getByPK( $_GET["uid"] );
                $esta_direccion = DireccionDAO::getByPK( $este_usuario->getIdDireccion() );
                if(is_null($esta_direccion))
                    $esta_direccion = new Direccion();
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_usuario->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
                if($este_usuario->getActivo())
                {
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
		}
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_usuario );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_usuario",
                                "id_direccion"
			 ));
                
                $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() , $esta_direccion->getIdCiudad());
                $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll() , $este_usuario->getIdRol());
        
        $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::getAll() , $este_usuario->getIdMoneda());
        
        $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll() , $este_usuario->getIdClasificacionCliente());
        
        $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::getAll(), $este_usuario->getIdClasificacionProveedor() );
        
                
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
                
                if(!is_null($este_usuario->getIdDireccion()))
                {
                    $page->addComponent( new TitleComponent("Direccion") );
                    
                    $form = new DAOFormComponent($esta_direccion);
                    
                    $form->hideField(
                            array(
                                "id_direccion",
                                "id_usuario_ultima_modificacion"
                            )
                            );
                    
                    $form->setEditable(false);
                    
                    $form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad());
                    
                    $page->addComponent($form);
                    
                }
                
                if(!is_null($este_usuario->getIdDireccionAlterna()))
                {
                    $page->addComponent( new TitleComponent("Direccion") );
                    $esta_direccion = DireccionDAO::getByPK($este_usuario->getIdDireccionAlterna());
                    $form = new DAOFormComponent($esta_direccion);
                    
                    $form->hideField(
                            array(
                                "id_direccion"
                            )
                            );
                    
                    $form->setEditable(false);
                    
                    $form->createComboBoxJoin("id_ciudad", "id_ciudad", CiudadDAO::getAll());
                    
                    $page->addComponent($form);
                    
                }
                
                $page->addComponent( new TitleComponent("Ventas") , 2 );
                
                $page->addComponent( new TitleComponent("Compras") , 2 );
                
                $page->addComponent( new TitleComponent("Ordenes de servicio") , 2 );
		
		$page->render();
