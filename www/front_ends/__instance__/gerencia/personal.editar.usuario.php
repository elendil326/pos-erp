<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "uid", "GET", "Este usuario no existe." );
		$este_usuario = UsuarioDAO::getByPK( $_GET["uid"] );
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar usuario " . $este_usuario->getNombre() , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $este_usuario );
		$form->hideField( array( 
				"id_usuario",
                                "id_direccion",
                                "id_direccion_alterna"
			 ));
                
                $form->addApiCall( "api/personal/rol/editar/" );
                
                $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll(), $este_usuario->getIdRol() );

                $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ), $este_usuario->getIdMoneda() ) );

                $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $este_usuario->getIdClasificacionCliente() );

                $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ), $este_usuario->getIdClasificacionProveedor() );
            
		$page->addComponent( $form );
                
		$page->render();
