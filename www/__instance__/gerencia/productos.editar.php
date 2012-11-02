<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                //
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este producto no existe." );
		
		$este_producto = ProductoDAO::getByPK( $_GET["pid"] );
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Editar  " . $este_producto->getNombreProducto()  , 2 ));

		//
		// Forma de usuario
		// 
		$form = new DAOFormComponent( $este_producto );
		$form->hideField( array( 
                                "id_producto",
                                "activo",
			 				));
			
		$form->sendHidden("id_producto");
                
		$form->addApiCall( "api/producto/editar/", "POST" );
		
		$form->renameField( array("descripcion" => "descripcion_producto") );
		
		$form->onApiCallSuccessRedirect("productos.ver.php?pid=" . $este_producto->getIdProducto() );

		$form->createComboBoxJoinDistintName("id_unidad_compra","id_unidad_medida" ,"abreviacion", UnidadMedidaDAO::search(new UnidadMedida(array(
	    "activa" => 1
	))));
	
	
	$form->createComboBoxJoinDistintName("id_unidad", "id_unidad_medida", "abreviacion", UnidadMedidaDAO::search(new UnidadMedida(array(
	    "activa" => 1
	))));

		$form->createComboBoxJoin( "metodo_costeo", "metodo_costeo", array( "precio" , "costo" ), $este_producto->getMetodoCosteo());

		$form->createComboBoxJoin( "compra_en_mostrador", "compra_en_mostrador", array( array( "id" => 1 , "caption" => "si" ), 
		                            array( "id" => 0 , "caption" => "no" ) ), $este_producto->getCompraEnMostrador() );

		$page->addComponent( $form );
                
		$page->render();
