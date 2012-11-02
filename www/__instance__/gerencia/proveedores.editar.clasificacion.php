<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "cid", "GET", "Esta clasificacion de proveedor no existe." );
		$esta_clasificacion = ClasificacionProveedorDAO::getByPK( $_GET["cid"] );
                //titulos
	$page->addComponent( new TitleComponent( "Editar clasificacion de proveedor: ".$esta_clasificacion->getNombre() ) );

	//forma de nuevo paquete
	$form = new DAOFormComponent( $esta_clasificacion );
	
	$form->hideField( array( 
			"id_clasificacion_proveedor"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/proveedor/clasificacion/editar/", "GET" );
	
//	$form->makeObligatory(array( 
//			"nombre"
//		));
	
//	$form->createComboBoxJoin( "id_ciudad", "nombre", CiudadDAO::getAll( ) );
//	$form->createComboBoxJoin( "id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll( ) );
	
	$page->addComponent( $form );


	//render the page
		$page->render();
