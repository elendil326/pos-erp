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
		
        $form->renameField( array( 
			"id_ciudad" => "ciudad",
		));
		
	$form->addApiCall( "api/empresa/nuevo/" );
	$page->addComponent( $form );


	//render the page
                
		$page->render();
