<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta categoria de producto no existe." );
		$esta_categoria = ClasificacionProductoDAO::getByPK( $_GET["cid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar clasificacion de producto " . $esta_categoria->getNombre()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $esta_categoria );
		$form->hideField( array( 
                                "id_clasificacion_producto",
                                "activa",
                                
			 ));
                
                $form->addApiCall( "api/producto/categoria/editar/", "GET" );
                
//                $form->addField("id_producto", "Productos", "text","","productos");
//                $form->createListBoxJoin("id_producto", "nombre_producto", ProductoDAO::search( new Producto( array( "activo" => 1 ) ) ));
//
                
//                $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad() );
                
//                $form->createComboBoxJoinDistintName( "id_gerente", "id_usuario" , "nombre", UsuarioDAO::search(new Usuario( array( "id_rol" => 2 ) )), $esta_sucursal->getIdGerente() );
                
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
