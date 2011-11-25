<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //titulos
	$page->addComponent( new TitleComponent( "Procesar Producto" ) );

	//forma de traspaso a almacen
	$form = new DAOFormComponent( array( new ProductoAlmacen(), new ProductoAlmacen() ) );
	
//	$form->hideField( array( 
//			"id_traspaso",
//                        "id_usuario_programa",
//                        "id_usuario_envia",
//                        "fecha_envio",
//                        "id_usuario_recibe",
//                        "fecha_recibo",
//                        "estado",
//                        "cancelado",
//                        "completo"
//		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/inventario/procesar_producto", "GET" );
	
//	$form->makeObligatory(array( 
//			"fecha_envio_programada",
//                        "id_almacen_recibe",
//                        "id_almacen_envia"
//		));
	
//	$form->createComboBoxJoinDistintName("id_almacen_recibe","id_almacen", "nombre", AlmacenDAO::search( new Almacen( array("activo" => 1) ) ) );
//	$form->createComboBoxJoinDistintName("id_almacen_envia","id_almacen", "nombre", AlmacenDAO::search( new Almacen( array("activo" => 1) ) ) );
        

//        $form->renameField( array( "id_producto" => "productos" ) );
	
	$page->addComponent( $form );
                
		$page->render();
