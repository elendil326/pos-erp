<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Este cliente no existe." );
		$este_usuario = UsuarioDAO::getByPK( $_GET["cid"] );
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
		$menu = new MenuComponent();
		$menu->addItem("Editar el perfil de este cliente", "clientes.editar.perfil.php?cid=".$_GET["cid"]);
                
                $menu->addItem("Editar este cliente", "clientes.editar.php?cid=".$_GET["cid"]);
                
		$page->addComponent( $menu);
		
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
		
		$page->render();
