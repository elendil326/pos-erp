<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Productos" ) );
		$page->addComponent( new MessageComponent( "Lista de productos en sus empresas" ) );
		
		$tabla = new TableComponent( 
			array(
				"id_producto" 	=> "id_producto",
				"codigo_producto" => "codigo_producto",
				"nombre_producto"	=> "nombre_producto",
				"descripcion" 		=> "descripcion",
				"activo" 			=> "activo"
			),
			ProductosController::Lista()
		);
		
		$tabla->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );

		$page->render();
