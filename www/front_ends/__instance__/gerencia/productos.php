<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent(new TitleComponent( "Productos" ));

		$page->nextTab("Lista");
		
		$cols = array(
			"codigo_producto" 	=> "Codigo producto",
			"nombre_producto"	=> "Nombre Producto",
			"precio" 			=> "Precio"
		);
		
		
		$tabla = new TableComponent( 
			$cols,
			ProductosController::Lista()
		);
	
	
		function precio($precio, $obj){
			if($obj["metodo_costeo"] === "costo"){
				return FormatMoney($obj["costo_estandar"]);
			}
			return FormatMoney($precio);
			
		}
		$tabla->addColRender( "precio", "precio" );
                
		$tabla->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
		
		$page->nextTab("Categorias");
		$page->nextTab("Estadisticas");
		
		$page->render();
