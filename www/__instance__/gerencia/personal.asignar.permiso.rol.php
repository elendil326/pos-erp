<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Asignacion de permiso a un rol" ) );

	//forma de asignacion de permiso a un rol
	$form = new DAOFormComponent( array( new Rol(), new Permiso() ) );
	
	$form->hideField( array( 
			"nombre",
                        "descripcion",
                        "descuento",
                        "salario",
                        "permiso"
		 ));
        
        $form->createComboBoxJoin( "id_rol", "nombre", RolDAO::getAll( ) );
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
	
	$form->addApiCall( "api/personal/rol/permiso/asignar" );
        $form->onApiCallSuccessRedirect("personal.lista.permiso.rol.php");
	
	$form->makeObligatory(array( 
			"id_rol",
                        "id_permiso"
		));
	
	
	$page->addComponent( $form );


	//render the page
                
		$page->render();
