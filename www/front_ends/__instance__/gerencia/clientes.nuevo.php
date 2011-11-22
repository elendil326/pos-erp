<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//titulos
	$page->addComponent( new TitleComponent( "Nuevo cliente" ) );

	//forma de nuevo cliente
	$page->addComponent( new TitleComponent( "Datos personales" , 3 ) );
	$form = new DAOFormComponent( array( new Usuario(), new Direccion() ) );
	

	
	$form->hideField( array( 
			"id_usuario",
			"id_direccion",
			"id_direccion_alterna",
			"id_sucursal",
			"id_rol",	
			"id_clasificacion_cliente",
			"id_clasificacion_proveedor",	
			"id_moneda",
			"fecha_asignacion_rol",
			"comision_ventas",
			"fecha_alta"	,
			"fecha_baja",
			"activo",
			"password",	
			"last_login",
			"salario",
			"saldo_del_ejercicio",
			"dia_de_pago",
			"dias_de_embarque",
			"id_direccion",
			//"id_ciudad",
			"ultima_modificacion",
			"id_usuario_ultima_modificacion"
		 ));


	$form->renameField( array( 
			"nombre" 			=> "razon_social",
			"codigo_usuario"	=> "codigo_cliente"
		));
	
	$form->addApiCall( "api/cliente/nuevo/" );
	
	$form->makeObligatory(array( 
			"password",
			"clasificacion_cliente",
			"codigo_cliente",
			"razon_social"
		));	
	
	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() );
	
	
	//$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll() );
	
	$page->addComponent( $form );


	//render the page
	$page->render();


