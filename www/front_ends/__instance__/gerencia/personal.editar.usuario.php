<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "uid", "GET", "Este usuario no existe." );
		$este_usuario = UsuarioDAO::getByPK( $_GET["uid"] );
		$esta_direccion = DireccionDAO::getByPK($este_usuario->getIdDireccion());
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar usuario " . $este_usuario->getNombre() , 2 ));

		//
		// Forma de usuario
		// 
                if(is_null($esta_direccion))
                    $esta_direccion = new Direccion();
		$form = new DAOFormComponent( array( $este_usuario, $esta_direccion ) );
		$form->hideField( array( 
				"id_usuario",
                                "id_direccion",
                                "id_direccion_alterna",
                                "id_sucursal",
                                "fecha_asignacion_rol",
                                "fecha_alta",
                                "fecha_baja",
                                "activo",
                                "last_login",
                                "consignatario",
                                "id_direccion",
                                "ultima_modificacion",
                                "id_usuario_ultima_modificacion"
			 ));
                $form->sendHidden("id_usuario");
                
                $form->setValueField("password", "");
                
                $form->addApiCall( "api/personal/usuario/editar/" );
                $form->onApiCallSuccessRedirect("personal.lista.usuario.php");
                
                $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad() );
                
                $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll(), $este_usuario->getIdRol() );

                $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ),$este_usuario->getIdMoneda() );

                $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $este_usuario->getIdClasificacionCliente() );

                $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ), $este_usuario->getIdClasificacionProveedor() );
				
				$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa" ,"nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"venta"))));
				$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"compra"))));
				$form->createComboBoxJoin( "tarifa_compra_obtenida", "tarifa_compra_obtenida", array("rol", "proveedor", "cliente","usuario") );
				$form->createComboBoxJoin( "tarifa_venta_obtenida", "tarifa_venta_obtenida", array("rol", "proveedor", "cliente","usuario") );
            
		$page->addComponent( $form );
                
		$page->render();
