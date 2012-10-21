<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	function funcion_cancelado( $cancelado )
	{
		return $cancelado ? "Cancelado" : "Activo" ;
	}


	$page = new GerenciaTabPage( );

	$page->nextTab( "Gastos" );

	$page->addComponent( new TitleComponent( "Gastos", 3 ) );

	$lista = CargosYAbonosController::ListaGasto(  );

	$tabla = new TableComponent(
		array(
			"id_empresa"	 		=> "Empresa",
			"id_usuario"			=> "Usuario",
			"id_concepto_gasto"		=> "Concepto",
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
	$form->createComboBoxJoin( "id_concepto_gasto", "nombre", ConceptoIngresoDAO::getAll( ) );
	$form->createComboBoxJoin( "id_caja", "descripcion", CajaDAO::getAll( ) );

	//this should be post
	$form->addApiCall("api/cargosyabonos/gasto/nuevo", "GET");


	$form->renameField( array( "fecha_del_gasto" => "fecha_gasto" ) );
	$form->setType( "fecha_gasto", "date" );

	$form->makeObligatory( array( "fecha_gasto", "monto", "id_empresa" ) );
	$page->addComponent( $form );


	$page->nextTab( "Ingresos" );
	$page->addComponent( new TitleComponent( "Ingresos", 3 ) );
	$lista = CargosYAbonosController::ListaIngreso( );

	$tabla = new TableComponent( array(
				"id_ingreso"			=> "id_ingreso",
				"id_empresa"	 		=> "id_empresa",
				"id_usuario"			=> "id_usuario",
				"id_concepto_ingreso"	=> "concpto",
				"fecha_del_ingreso"		=> "fecha",
				"monto"					=> "monto"
			),
			$lista["resultados"]
		);
		
		$tabla->addColRender( "cancelado", "funcion_cancelado" );
		$page->addComponent( $tabla );

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
	$form->addApiCall( "api/cargosyabonos/ingreso/nuevo", "POST" );
	$form->renameField( array("fecha_del_ingreso" => "fecha_ingreso") );
	$page->addComponent( $form );

	$page->render( );
