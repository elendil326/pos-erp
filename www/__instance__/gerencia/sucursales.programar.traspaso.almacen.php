<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                 //titulos
	$page->addComponent( new TitleComponent( "Programar traspaso de almacen" ) );

	//forma de traspaso a almacen
	$form = new DAOFormComponent( array( new Traspaso() ) );
	
	$form->hideField( array( 
			"id_traspaso",
                        "id_usuario_programa",
                        "id_usuario_envia",
                        "fecha_envio",
                        "id_usuario_recibe",
                        "fecha_recibo",
                        "estado",
                        "cancelado",
                        "completo"
		 ));


//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/sucursal/almacen/traspaso/programar", "GET" );
	
	$form->makeObligatory(array( 
			"fecha_envio_programada",
                        "id_almacen_recibe",
                        "id_almacen_envia"
		));
	
	$form->createComboBoxJoinDistintName("id_almacen_recibe","id_almacen", "nombre", AlmacenDAO::search( new Almacen( array("activo" => 1) ) ) );
	$form->createComboBoxJoinDistintName("id_almacen_envia","id_almacen", "nombre", AlmacenDAO::search( new Almacen( array("activo" => 1) ) ) );
        
        $form->addField("id_producto", "Productos", "text","","productos");
        $form->createListBoxJoin("id_producto", "nombre_producto", ProductoDAO::search( new Producto( array( "activo" => 1 ) ) ));

        $form->renameField( array( "id_producto" => "productos" ) );
	
	$page->addComponent( $form );
                
		$page->render();
