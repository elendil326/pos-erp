<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este producto no existe." );
		$este_producto = ProductoDAO::getByPK( $_GET["pid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_producto->getNombreProducto() , 2 ));

		
		//
		// Menu de opciones
		//
                if($este_producto->getActivo())
                {
                    $menu = new MenuComponent();
                    $menu->addItem("Editar este producto", "productos.editar.php?pid=".$_GET["pid"]);
                    //$menu->addItem("Desactivar este producto", null);

                    $btn_eliminar = new MenuItem("Desactivar este producto", null);
                    $btn_eliminar->addApiCall("api/producto/desactivar", "GET");
                    $btn_eliminar->onApiCallSuccessRedirect("productos.lista.php");
                    $btn_eliminar->addName("eliminar");

                    $funcion_eliminar = " function eliminar_producto(btn){".
                                "if(btn == 'yes')".
                                "{".
                                    "var p = {};".
                                    "p.id_producto = ".$_GET["pid"].";".
                                    "sendToApi_eliminar(p);".
                                "}".
                            "}".
                            "      ".
                            "function confirmar(){".
                            " Ext.MessageBox.confirm('Desactivar', 'Desea eliminar este producto?', eliminar_producto );".
                            "}";

                    $btn_eliminar->addOnClick("confirmar", $funcion_eliminar);

                    $menu->addMenuItem($btn_eliminar);

                    $page->addComponent( $menu);
                }
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_producto );
		$form->setEditable(false);
		//$form->setEditable(false);		
		$form->hideField( array( 
				"id_producto",
			 ));
		$form->makeObligatory(array( 
				"compra_en_mostrador",
				"costo_estandar",
				"nombre_producto",
				"id_empresas",
				"codigo_producto",
				"metodo_costeo",
				"activo"
			));
                $form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::getAll(), $este_producto->getIdUnidad() );
                
		$page->addComponent( $form );
		
                $page->addComponent( new TitleComponent( "Precios segun tipo de cliente" ) );
        
                $tabla = new TableComponent(
                        array(
                            "id_clasificacion_cliente"  => "Clasificacion de cliente",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioProductoTipoClienteDAO::search( new PrecioProductoTipoCliente( array( "id_producto" => $_GET["pid"] ) ) )
                        );
                function funcion_es_margen_utilidad($es_margen_utilidad)
                {
                    return $es_margen_utilidad ? "Margen de Utilidad" : "Precio" ;
                }
                
                function funcion_clasificacion_cliente($id_clasificacion_cliente)
                {
                    return ClasificacionClienteDAO::getByPK($id_clasificacion_cliente) ? 
                            ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)->getNombre() : "------";
                }
                
                $tabla->addColRender("es_margen_utilidad", "funcion_es_margen_utilidad");
                $tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
                
                $page->addComponent($tabla);

                $page->addComponent( new TitleComponent( "Precios segun usuario" ) );
                
                $tabla = new TableComponent(
                        array(
                            "id_usuario"                => "Usuario",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioProductoUsuarioDAO::search( new PrecioProductoUsuario( array( "id_producto" => $_GET["pid"] ) ) )
                        );
                
                function funcion_usuario($id_usuario)
                {
                    return UsuarioDAO::getByPK($id_usuario) ? 
                            UsuarioDAO::getByPK($id_usuario)->getNombre() : "------";
                }
                
                $tabla->addColRender("es_margen_utilidad", "funcion_es_margen_utilidad");
                $tabla->addColRender("id_usuario", "funcion_usuario");
                
                $page->addComponent($tabla);

                $page->addComponent( new TitleComponent( "Precios segun rol" ) );
                
                $tabla = new TableComponent(
                        array(
                            "id_rol"                    => "Rol",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioProductoRolDAO::search( new PrecioProductoRol( array( "id_producto" => $_GET["pid"] ) ) )
                        );
                
                function funcion_rol($id_rol)
                {
                    return RolDAO::getByPK($id_rol) ? 
                            RolDAO::getByPK($id_rol)->getNombre() : "------";
                }
                
                $tabla->addColRender("es_margen_utilidad", "funcion_es_margen_utilidad");
                $tabla->addColRender("id_rol", "funcion_rol");
                
                $page->addComponent($tabla);
                
		$page->render();
