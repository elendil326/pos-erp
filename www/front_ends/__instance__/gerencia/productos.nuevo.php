<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Titulo
	// 
	$page->addComponent( new TitleComponent( "Nuevo Producto" , 2 ));


	//
	// Forma de nuevo producto
	// 
	$form = new DAOFormComponent( new  Producto() );
	$form->addApiCall("api/producto/nuevo/", "GET");
	$form->onApiCallSuccessRedirect( "productos.lista.php" );
	
	$form->hideField( array( 
			"id_producto",
		 ));

	$form->makeObligatory(array( 
			"compra_en_mostrador",
			"costo_estandar",
			"nombre_producto",
			"id_empresas",
			"codigo_producto",
			"metodo_costeo",
			"activo"
		));
	
    $form->createComboBoxJoin("id_unidad", "nombre", UnidadDAO::search( new Unidad( array( "activa" => 1 ) ) ));
	$page->addComponent( $form );


	//
	// Render the page
	// 
	$page->render();