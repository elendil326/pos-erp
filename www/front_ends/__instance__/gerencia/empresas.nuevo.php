<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
	
        //titulos
	$page->addComponent( new TitleComponent( "Nueva empresa" ) );

	//forma de nueva empresa
	$page->addComponent( new TitleComponent( "Datos de la empresa" , 3 ) );
	$form = new DAOFormComponent( array( new Empresa(), new Direccion() ) );
	
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
		
        $form->renameField( array( 
			"id_ciudad" => "ciudad",
		));
        
        $form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() );
		
	$form->addApiCall( "api/empresa/nuevo/" );
	$page->addComponent( $form );


	//render the page
	$page->render();





