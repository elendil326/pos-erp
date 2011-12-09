<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Realizar seguimiento a una orden de servicio ", 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( new SeguimientoDeServicio() );
		$form->hideField( array( 
                                "id_seguimiento_de_servicio",
                                "id_usuario",
                                "id_sucursal",
                                "fecha_seguimiento"
                                
			 ));
                
                $form->addApiCall( "api/servicios/orden/seguimiento/", "GET" );
                
//                $form->addField("id_producto", "Productos", "text","","productos");
//                $form->createListBoxJoin("id_producto", "nombre_producto", ProductoDAO::search( new Producto( array( "activo" => 1 ) ) ));
//
                
                $form->createComboBoxJoin( "id_orden_de_servicio", "id_orden_de_servicio", OrdenDeServicioDAO::search(new OrdenDeServicio( array( "activa" => 1 ) )) );
                
                $form->createComboBoxJoinDistintName( "id_localizacion", "id_sucursal" , "razon_social", SucursalDAO::search(new Sucursal( array( "activa" => 1 ) )) );
                
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
                
		$page->addComponent( $form );
                
		$page->render();
