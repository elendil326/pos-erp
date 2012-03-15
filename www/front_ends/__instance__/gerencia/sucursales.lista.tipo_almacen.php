<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//titulos
	$page->addComponent(new TitleComponent("Nuevo tipo de almacen"));
	$a = AlmacenesController::Buscar();
	$tabla = new TableComponent(array(
	    "id_tipo_almacen" => "Tipo de Almacen",
	    "descripcion" => "descripcion"
	), $a["resultados"]);

	$tabla->addOnClick("id_tipo_almacen", "(function(a){window.location = 'sucursales.tipo_almacen.ver.php?tid='+a;})");

	$page->addComponent($tabla);


	//render the page
	$page->render();