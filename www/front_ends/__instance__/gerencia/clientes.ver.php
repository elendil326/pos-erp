<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Este cliente no existe." );
		
		$este_usuario = UsuarioDAO::getByPK( $_GET["cid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_usuario->getNombre() , 2 ));

		
		//
		// Menu de opciones
		// 
        if($este_usuario->getActivo()){
	
            $menu = new MenuComponent();

            //$menu->addItem("Editar el perfil de este cliente", "clientes.editar.perfil.php?cid=".$_GET["cid"]);

            $menu->addItem("Editar este cliente", "clientes.editar.php?cid=".$_GET["cid"]);

            $page->addComponent( $menu);
        }


		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_usuario );
		$form->setEditable(false);
                
		$form->hideField( array( 
				"id_usuario",
				"salario",
				"id_rol",
				"comision_ventas",
				"dia_de_revision",
				"id_clasificacion_proveedor",
				"id_direccion",
				"id_direccion_alterna",
				"fecha_asignacion_rol",
				"activo",
				"password"
			 ));
                
                
                
                
        $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll() , $este_usuario->getIdRol());
        $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::getAll() , $este_usuario->getIdMoneda());
        $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll() , $este_usuario->getIdClasificacionCliente());
        $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::getAll(), $este_usuario->getIdClasificacionProveedor() );        
		$page->addComponent( $form );



		$direccion = $este_usuario->getIdDireccion();
		$direccionObj = DireccionDAO::getByPK( $direccion );
		$dform = new DAOFormComponent( $direccionObj );
		$dform->setEditable(false);		
		$form->hideField( array( 
				"id_direccion"
			 ));
		$page->addComponent( $dform );		
		
		$page->render();
