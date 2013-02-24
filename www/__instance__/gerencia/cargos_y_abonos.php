<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");


	$page = new GerenciaTabPage( );

	$page->nextTab( "Gastos" );

	/*
	 * Nuevo Gasto
	 **/
	$page->addComponent( new TitleComponent( "Nuevo Gasto" , 3 ) );
	$form = new DAOFormComponent(array(
		new Gasto( ),
		new ConceptoGasto( )
	));

	$form->hideField( array(
		"activo",
		"cancelado",
		"id_usuario",
		"id_gasto",
		"motivo_cancelacion",
		"fecha_de_registro",
		"id_sucursal",
		"id_caja",
		"id_orden_de_servicio",
		"nombre"
	));

	$form->createComboBoxJoin( "id_empresa", "razon_social", EmpresaDAO::getAll( ) );
	$form->createComboBoxJoin( "id_concepto_gasto", "nombre", ConceptoGastoDAO::getAll( ) );
	$form->createComboBoxJoin( "id_caja", "descripcion", CajaDAO::getAll( ) );
	$form->makeObligatory( array( "fecha_del_gasto", "monto", "id_empresa", "id_concepto_gasto" ) );

	//this should be post
	$form->addApiCall("api/cargosyabonos/gasto/nuevo", "GET");
	$form->setCaption("id_empresa", "Empresa");
	$form->setCaption("id_concepto_gasto", "Concepto");
	$form->renameField( array(
		"fecha_del_gasto" => "fecha_gasto", 
	) );



	$form->setType( "fecha_gasto", "date" );

	$page->addComponent( $form );

	/*
	 * Lista de gastos
	 **/
	$page->addComponent( new TitleComponent( "Gastos", 3 ) );
	$lista = CargosYAbonosController::ListaGasto( );
	$tabla = new TableComponent(
		array(
			"id_empresa"	 		=> "Empresa",
			"id_usuario"			=> "Usuario",
			"descripcion"			=> "Concepto",
			"fecha_del_gasto"		=> "Fecha",
			"monto"					=> "Monto"
		),
		$lista["resultados"]
	);

	$tabla->addColRender( "id_empresa", "R::RazonSocialFromIdEmpresa");
	$tabla->addColRender( "monto", "R::MoneyFromDouble");
	$tabla->addColRender( "fecha_del_gasto", "R::FriendlyDateFromUnixTime");
	$tabla->addColRender( "id_usuario", "R::UserFullNameFromId");
	$tabla->addColRender( "cancelado", "funcion_cancelado" );
	$page->addComponent( $tabla );





	$page->nextTab( "Ingresos" );


	/*
	 * Nuevo Ingreso
	 **/
	$page->addComponent( new TitleComponent( "Nuevo Ingreso" , 3 ) );

	$form = new DAOFormComponent( array(
		new Ingreso( ),
		new ConceptoIngreso( )
	));

	$form->hideField( array(
		"activo",
		"cancelado",
		"id_usuario",
		"id_ingreso",
		"motivo_cancelacion",
		"fecha_de_registro",
		"id_sucursal",
		"id_caja"
	));

	$form->createComboBoxJoin( "id_empresa", "razon_social", EmpresaDAO::getAll( ) );
	$form->createComboBoxJoin( "id_concepto_ingreso", "nombre", ConceptoIngresoDAO::getAll( ) );
	$form->createComboBoxJoin( "id_caja", "descripcion", CajaDAO::getAll( ) );
	$form->makeObligatory( array( "fecha_del_ingreso", "monto", "id_empresa", "id_concepto_ingreso" ) );
	$form->renameField( array(
		"fecha_del_ingreso" => "fecha_ingreso", 
	) );
	$form->setCaption("id_empresa", "Empresa");
	$form->setCaption("id_concepto_ingreso", "Concepto");
	$form->setType( "fecha_ingreso", "date" );
	$form->addApiCall( "api/cargosyabonos/ingreso/nuevo", "POST" );
	
	$page->addComponent( $form );



	/*
	 * Lista de ingresos
	 **/
	$page->addComponent( new TitleComponent( "Ingresos", 3 ) );
	$lista = CargosYAbonosController::ListaIngreso( );

	$tabla = new TableComponent( array(
				"id_empresa"	 		=> "Empresa",
				"id_usuario"			=> "Usuario",
				"descripcion"			=> "Concepto",
				"fecha_del_ingreso"		=> "Fecha",
				"monto"					=> "Monto"
			),
			$lista["resultados"]
		);

	$tabla->addColRender( "cancelado", "funcion_cancelado" );
	$tabla->addColRender( "id_empresa", "R::RazonSocialFromIdEmpresa");
	$tabla->addColRender( "monto", "R::MoneyFromDouble");
	$tabla->addColRender( "fecha_del_ingreso", "R::FriendlyDateFromUnixTime");
	$tabla->addColRender( "id_usuario", "R::UserFullNameFromId");
	$tabla->addColRender( "cancelado", "funcion_cancelado" );
	$page->addComponent( $tabla );



	/*
	 * Conceptos de ingresos
	 **/
	$page->nextTab( "Conceptos" );
	$page->addComponent( new TitleComponent( "Nuevo concepto de ingreso" , 3 ) );
	$form = new DAOFormComponent( new ConceptoIngreso() );
	$form->addApiCall( "api/cargosyabonos/ingreso/concepto/nuevo", "POST" );
	$form->hideField( array( "id_concepto_ingreso" ) );
	$form->makeObligatory( array( "nombre" ) );
	$page->addComponent( $form );

	/*
	 * Conceptos de gasto
	 **/
	$form = new DAOFormComponent( new ConceptoGasto() );
	$page->addComponent( new TitleComponent( "Nuevo concepto de gasto" , 3 ) );
	$form->addApiCall( "api/cargosyabonos/gasto/concepto/nuevo", "POST" );
	$form->hideField( array( "id_concepto_gasto" ) );
	$form->makeObligatory( array( "nombre" ) );
	$page->addComponent( $form );

	$page->render( );
