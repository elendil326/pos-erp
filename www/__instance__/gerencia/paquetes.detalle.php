<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

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
                
                if($este_paquete->getActivo())
                {
                    $menu->addItem("Editar este paquete", "paquetes.editar.php?pid=".$_GET["pid"]);

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
                }
                else
                {
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
                }
                
		$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_paquete );
		$form->setEditable(false);
		
		$form->hideField( array( 
				"id_paquete",
			 ));
		$page->addComponent( $form );
                
                $page->addComponent( new TitleComponent( "Productos en este paquete" ) , 3);
                
                $table = new TableComponent(
                        array(
                            "id_producto"   => "Producto",
                            "id_unidad"     => "Unidad",
                            "cantidad"      => "Cantidad"
                        ),
                         ProductoPaqueteDAO::search( new ProductoPaquete( array( "id_paquete" => $_GET["pid"] ) ) )
                        );
                
                function funcion_producto($id_producto)
                {
                    return ProductoDAO::getByPK($id_producto) ? ProductoDAO::getByPK($id_producto)->getNombreProducto() : "--------";
                }
                
                function funcion_unidad($id_unidad)
                {
                    return UnidadDAO::getByPK($id_unidad) ? UnidadDAO::getByPK($id_unidad)->getNombre() : "---------";
                }
                
                $table->addColRender("id_producto", "funcion_producto");
                
                $table->addColRender("id_unidad", "funcion_unidad");
                
                $table->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
                
                $page->addComponent($table);
                
                $page->addComponent( new TitleComponent( "Servicios en este paquete" ) , 3);
                
                $table = new TableComponent(
                        array(
                            "id_servicio"   => "Servicio",
                            "cantidad"      => "Cantidad"
                        ),
                         OrdenDeServicioPaqueteDAO::search( new OrdenDeServicioPaquete( array( "id_paquete" => $_GET["pid"] ) ) )
                        );
                
                function funcion_servicio($id_servicio)
                {
                    return ServicioDAO::getByPK($id_servicio) ? ServicioDAO::getByPK($id_servicio)->getNombreServicio() : "---------";
                }
                
                $table->addColRender("id_servicio", "funcion_servicio");
                
                $table->addOnClick( "id_servicio", "(function(a){ window.location = 'servicios.ver.php?sid=' + a; })" );
                
                $page->addComponent($table);
                
                $page->addComponent( new TitleComponent( "Precios segun tipo de cliente" ) );
        
                $tabla = new TableComponent(
                        array(
                            "id_clasificacion_cliente"  => "Clasificacion de cliente",
                            "es_margen_utilidad"        => "Precio o margen de utilidad",
                            "precio_utilidad"           => "Precio/Margen de Utilidad"
                        ),
                         PrecioPaqueteTipoClienteDAO::search( new PrecioPaqueteTipoCliente( array( "id_paquete" => $_GET["pid"] ) ) )
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
                         PrecioPaqueteUsuarioDAO::search( new PrecioPaqueteUsuario( array( "id_paquete" => $_GET["pid"] ) ) )
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
                         PrecioPaqueteRolDAO::search( new PrecioPaqueteRol( array( "id_paquete" => $_GET["pid"] ) ) )
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
