<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar gerencia"  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( new Sucursal() );
		$form->hideField( array( 
                                "id_direccion",
                                "fecha_apertura",
                                "fecha_baja",
                                "activa",
                                "rfc",
                                "razon_social",
                                "descripcion",
                                "saldo_a_favor",
                                "margen_utilidad",
                                "descuento"
			 ));
                
                $form->addApiCall( "api/sucursal/gerencia/editar/", "GET" );
                
//                $form->addField("id_producto", "Productos", "text","","productos");
//                $form->createListBoxJoin("id_producto", "nombre_producto", ProductoDAO::search( new Producto( array( "activo" => 1 ) ) ));
//
                
                $form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::search(new Sucursal( array( "activa" => 1 ) )));
                
                $form->createComboBoxJoinDistintName( "id_gerente", "id_usuario" , "nombre", UsuarioDAO::search(new Usuario( array( "id_rol" => 2 ) )));
                
//                $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll(), $este_usuario->getIdRol() );
//
//                $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ),$este_usuario->getIdMoneda() );
//
//                $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $este_usuario->getIdClasificacionCliente() );
//
//                $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ), $este_usuario->getIdClasificacionProveedor() );
            
                //$form->createComboBoxJoin("id_tipo_almacen", "descripcion", array_diff(TipoAlmacenDAO::getAll(), TipoAlmacenDAO::search( new TipoAlmacen( array( "id_tipo_almacen" => 2 ) ) ) ), $este_almacen->getIdTipoAlmacen() );
                
//                $form->renameField( array( 
//                    "id_ciudad" => "municipio"
//		));
                
                $form->makeObligatory( array( "id_sucursal", "id_gerente" ) );
                
		$page->addComponent( $form );
                
		$page->render();
