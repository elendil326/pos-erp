<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server/bootstrap.php");

	$page = new GerenciaTabPage();

	$page->addComponent(new MessageComponent("Personal"));

	$page->nextTab( "Activos" );

	$page->addComponent(new MessageComponent("Lista de usuarios"));

	$lista = PersonalYAgentesController::ListaUsuario(1);
	

	
	$tabla = new TableComponent(array(
	    "codigo_usuario" => "Codigo de usuario",
	    "nombre" => "Nombre",
	    "id_rol" => "Rol",
	    "activo" => "Activo"
	), $lista["resultados"]);

	$tabla->addColRender("id_rol", "funcion_rol");
	$tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
	$tabla->addColRender("id_clasificacion_proveedor", "funcion_clasificacion_proveedor");
	$tabla->addColRender("activo", "funcion_activo");
	$tabla->addColRender("consignatario", "funcion_consignatario");
	$tabla->addOnClick("id_usuario", "(function(a){window.location = 'personal.usuario.ver.php?uid=' + a;})");
	$page->addComponent($tabla);
	
	
	
	
	$page->nextTab( "Inactivos" );
	$page->addComponent(new MessageComponent("Lista de usuarios inactivos"));
	$lista = PersonalYAgentesController::ListaUsuario(0);	

	$tabla = new TableComponent(array(
	    "codigo_usuario" => "Codigo de usuario",
	    "nombre" => "Nombre",
	    "id_rol" => "Rol",
	    "activo" => "Activo"
	), $lista["resultados"]);

	$tabla->addColRender("id_rol", "funcion_rol");
	$tabla->addColRender("id_clasificacion_cliente", "funcion_clasificacion_cliente");
	$tabla->addColRender("id_clasificacion_proveedor", "funcion_clasificacion_proveedor");
	$tabla->addColRender("activo", "funcion_activo");
	$tabla->addColRender("consignatario", "funcion_consignatario");
	$tabla->addOnClick("id_usuario", "(function(a){window.location = 'personal.usuario.ver.php?uid=' + a;})");
	$page->addComponent($tabla);


	$page->nextTab( "Roles" );
	$page->addComponent( new TitleComponent( "Roles" ) );
	$page->addComponent( new MessageComponent( "Lista de roles de usuario" ) );

	$tabla = new TableComponent( 
		array(
		"nombre" => "Nombre",
		"descripcion"	=> "Descripcion",
		"salario" 			=> "Salario"
		),
		PersonalYAgentesController::ListaRol()
	);

	$tabla->addOnClick( "id_rol", "(function(a){window.location = 'personal.rol.ver.php?rid=' + a;})" );
	$page->addComponent( $tabla );

	//Nuevo rol
	$page->addComponent( new TitleComponent( "Nuevo Rol" ) );
	$page->addComponent( new TitleComponent( "Datos del rol" , 3 ) );
	$form = new DAOFormComponent( array( new Rol() ) );

	$form->hideField( array( 
		"id_rol"
	));

	$form->addApiCall( "api/personal/rol/nuevo/" );
	$form->onApiCallSuccessRedirect("personal.lista.rol.php");
	$form->makeObligatory(array("nombre"));

	$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa" ,"nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"venta"))));
	$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"compra"))));
	$page->addComponent( $form );

	$page->render();
