<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent(new TitleComponent( "Productos" ));

		$page->nextTab("Lista");
		
		
		$tabla = new TableComponent( 
			array(
				"codigo_producto" 	=> "Codigo producto",
				"nombre_producto"	=> "Nombre Producto",
				"precio" 			=> "Precio"
			),
			ProductosController::Lista()
		);

		$tabla->addColRender( "precio", "FormatMoney" );
                
		$tabla->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
		
		$page->nextTab("Categorias");
		$page->nextTab("Estadisticas");
		
		$page->render();
