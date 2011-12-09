<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "eid", "GET", "Esta empresa no existe." );
		$esta_empresa = EmpresaDAO::getByPK( $_GET["eid"] );
		$esta_direccion = DireccionDAO::getByPK($esta_empresa->getIdDireccion());
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar empresa " . $esta_empresa->getRazonSocial() , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( array($esta_empresa, $esta_direccion) );
		$form->hideField( array( 
                                "id_empresa",
                                "id_direccion",
                                "fecha_alta",
                                "fecha_baja",
                                "activo",
                                "id_direccion",
                                "ultima_modificacion",
                                "id_usuario_ultima_modificacion"
			 ));
                
                $form->addApiCall( "api/empresa/editar/" );
                
                $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad() );
                
//                $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll(), $este_usuario->getIdRol() );
//
//                $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ),$este_usuario->getIdMoneda() );
//
//                $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $este_usuario->getIdClasificacionCliente() );
//
//                $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ), $este_usuario->getIdClasificacionProveedor() );
            
		$page->addComponent( $form );
                
		$page->render();
