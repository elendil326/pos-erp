<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Asignacion de permiso a un usuario" ) );

	//forma de asignacion de permiso a un usuario
	$form = new DAOFormComponent( array( new Permiso() ) );
	
        $form->addField("id_usuario", "Id Usuario", "text");
        
	$form->hideField( array( 
                        "permiso"
		 ));
        
        $form->createComboBoxJoin( "id_usuario", "nombre", UsuarioDAO::search( new Usuario( array( "activo" => 1 ) ) ) );
        $form->createComboBoxJoin( "id_permiso", "permiso", PermisoDAO::getAll( ) );

//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente",
//                        "telefono"          => "telefono1",
//                        "correo_electronico"    => "email",
//                        "id_clasificacion_cliente"  => "clasificacion_cliente",
//                        "id_moneda"     => "moneda_del_cliente",
//                        "pagina_web"    => "direccion_web"
//		));
	
	$form->addApiCall( "api/personal/usuario/permiso/asignar" );
	
	$form->makeObligatory(array( 
			"id_usuario",
                        "id_permiso"
		));
	
	
	$page->addComponent( $form );


	//render the page
                
		$page->render();
