<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nuevo almacen" ) );

	//forma de nuevo almacen
	$form = new DAOFormComponent( array( new Almacen() ) );
	
	$form->hideField( array( 
			"id_almacen",
                        "activo"
		 ));

//
//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/sucursal/almacen/nuevo/" );
        $form->onApiCallSuccessRedirect("sucursales.lista.almacen.php");
	
	$form->makeObligatory(array( 
			"id_sucursal",
                        "nombre",
                        "id_empresa",
                        "id_tipo_almacen"
		));
	
	$form->createComboBoxJoin( "id_sucursal", "razon_social", SucursalDAO::search( new Sucursal( array( "activa" => 1 ) ) ) );
	$form->createComboBoxJoin( "id_empresa", "razon_social", EmpresaDAO::search( new Empresa( array( "activo" => 1 ) ) ) );
        $form->createComboBoxJoin("id_tipo_almacen", "descripcion", array_diff(TipoAlmacenDAO::getAll(), TipoAlmacenDAO::search( new TipoAlmacen( array( "id_tipo_almacen" => 2 ) ) ) ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
