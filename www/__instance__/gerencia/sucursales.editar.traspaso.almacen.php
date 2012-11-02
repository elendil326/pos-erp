<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "tid", "GET", "Este traspaso no existe." );
		$este_traspaso = TraspasoDAO::getByPK( $_GET["tid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar traspaso " . $este_traspaso->getIdTraspaso() ."
                    . De almacen: ".AlmacenDAO::getByPK($este_traspaso->getIdAlmacenEnvia())->getNombre()."
                        al almacen: ".AlmacenDAO::getByPK($este_traspaso->getIdAlmacenRecibe())->getNombre()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $este_traspaso );
		$form->hideField( array( 
                                "id_traspaso",
                                "id_usuario_programa",
                                "id_usuario_envia",
                                "fecha_envio",
                                "id_usuario_recibe",
                                "fecha_recibo",
                                "estado",
                                "cancelado",
                                "completo",
                                "id_almacen_envia",
                                "id_almacen_recibe"
			 ));
                
                $form->addApiCall( "api/sucursal/almacen/traspaso/editar/", "GET" );
                
                $form->addField("id_producto", "Productos", "text","","productos");
                $form->createListBoxJoin("id_producto", "nombre_producto", ProductoDAO::search( new Producto( array( "activo" => 1 ) ) ));

                $form->renameField( array( "id_producto" => "productos" ) );
                
                //$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad() );
                
//                $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll(), $este_usuario->getIdRol() );
//
//                $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ),$este_usuario->getIdMoneda() );
//
//                $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $este_usuario->getIdClasificacionCliente() );
//
//                $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ), $este_usuario->getIdClasificacionProveedor() );
            
                //$form->createComboBoxJoin("id_tipo_almacen", "descripcion", array_diff(TipoAlmacenDAO::getAll(), TipoAlmacenDAO::search( new TipoAlmacen( array( "id_tipo_almacen" => 2 ) ) ) ), $este_almacen->getIdTipoAlmacen() );
                
		$page->addComponent( $form );
                
		$page->render();
