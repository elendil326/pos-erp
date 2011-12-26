<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo Usuario" ) );

	//forma de nuevo usuario
	$page->addComponent( new TitleComponent( "Datos del usuario" , 3 ) );
	$form = new DAOFormComponent( array( new Usuario(), new Direccion() ,new Direccion()) );
	
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
                        "id_usuario_ultima_modificacion",
                        "id_direccion",
                        "ultima_modificacion",
                        "id_usuario_ultima_modificacion"
		 ));
        
        $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() );
		
		$form->renameField( array( 
			"id_ciudad" => "ciudad",
		));
        
        $form->addApiCall( "api/personal/usuario/nuevo/" );
        $form->onApiCallSuccessRedirect("personal.lista.usuario.php");
        
        $form->makeObligatory(array( 
			"nombre",
			"id_rol",
			"password",
			"codigo_usuario"
		));
		
	
        
        
        
        $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll() );
        
        $form->createComboBoxJoin( "id_moneda", "nombre", MonedaDAO::search( new Moneda( array( "activa" => 1 ) ) ) );
        
        $form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll() );
        
        $form->createComboBoxJoin( "id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::search( new ClasificacionProveedor( array( "activa" => 1 ) ) ) );
        
	$page->addComponent( $form );


	//render the page
                
		$page->render();
