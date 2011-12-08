<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage(  );


		//
		// Parametros necesarios
		// 
		$page->requireParam(  "pid", "GET", "Este producto no existe." );
		$este_rol = ProductoDAO::getByPK( $_GET["pid"] );
		
		
		//
		// Titulo de la pagina
		// 
		$page->addComponent( new TitleComponent( "Detalles de " . $este_rol->getNombreProducto() , 2 ));

		
		//
		// Menu de opciones
		// 
		$menu = new MenuComponent();
		$menu->addItem("Editar este producto", "aa");
		$menu->addItem("Desactivar este producto", "da");		
		//$page->addComponent( $menu);
		
		//
		// Forma de producto
		// 
		$form = new DAOFormComponent( $este_rol );
		$form->setEditable(false);
		$form->setEditable(false);		
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
		
		$page->render();
