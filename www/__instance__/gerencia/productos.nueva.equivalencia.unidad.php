<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                //titulos
	$page->addComponent( new TitleComponent( "Nueva equivalencia entre unidades" ) );

	//forma de nueva equivalencia entre unidades
	$form = new DAOFormComponent( array( new UnidadEquivalencia() ) );
	
//	$form->hideField( array( 
//			"id_clasificacion_producto",
//                        "activa"
//		 ));

//
//	$form->renameField( array( 
//			"nombre" 			=> "razon_social",
//			"codigo_usuario"	=> "codigo_cliente"
//		));
	
	$form->addApiCall( "api/producto/unidad/nueva_equivalencia/" , "GET");
	
	$form->makeObligatory(array( 
			"id_unidad",
                        "id_unidades",
                        "equivalencia"
		));
	
	$form->createComboBoxJoin( "id_unidad", "nombre", UnidadDAO::search( new Unidad( array( "activa" => 1 ) ) ) );
        
//        $form->renameField( array( "id_unidades" => "id_unidad" ) );
//        
        $form->createComboBoxJoinDistintName( "id_unidades","id_unidad", "nombre", UnidadDAO::search( new Unidad( array( "activa" => 1 ) ) ) ) ;
//        
//        $form->renameField( array( "id_unidad" => "id_unidades" ) );
	
	$page->addComponent( $form );
        
        //render the page
		$page->render();
