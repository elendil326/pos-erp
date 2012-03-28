<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Titulo
	// 
	$page->addComponent(new TitleComponent("Nuevo Producto", 2));


	//
	// Forma de nuevo producto
	// 
	$form = new DAOFormComponent(new Producto());
	$form->addApiCall("api/producto/nuevo/");
	$form->onApiCallSuccessRedirect("productos.lista.php");
	$form->renameField(array( "precio" => "precio_de_venta" ));
	$form->hideField(array(
	    "id_producto"
	));

	$form->makeObligatory(array(
	    "compra_en_mostrador",
	    "nombre_producto",
	    "id_empresas",
	    "codigo_producto",
	    "metodo_costeo",
	    "activo",
	    "id_unidad_compra"
	));

	/*$form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::search(new Unidad(array(
	    "activa" => 1
	))));*/
	
	/*
	$form->createComboBoxJoinDistintName("id_unidad_compra", "id_unidad", "nombre", UnidadDAO::search(new Unidad(array(
	    "activa" => 1
	))));*/
	
	$form->createComboBoxJoin("metodo_costeo", "metodo_costeo", array(
	    "precio",
	    "costo"
	));
	
	$form->createComboBoxJoin("compra_en_mostrador", "compra_en_mostrador", array(
	    array(
	        "id" => 1,
	        "caption" => "si"
	    ),
	    array(
	        "id" => 0,
	        "caption" => "no"
	    )
	), 1);
	
	$form->createComboBoxJoin("activo", "activo", array(
	    array(
	        "id" => 1,
	        "caption" => "si"
	    ),
	    array(
	        "id" => 0,
	        "caption" => "no"
	    )
	), 1);
	
	$page->addComponent($form);

	//
	// Render the page
	// 
	$page->render();