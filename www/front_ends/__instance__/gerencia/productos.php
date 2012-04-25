<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent(new TitleComponent( "Productos" ));

		$page->nextTab("Lista");
		
		
		$tabla = new TableComponent( 
			array(
				"codigo_producto" => "codigo_producto",
				"nombre_producto"	=> "nombre_producto",
				"descripcion" 		=> "descripcion",
				"activo" 			=> "activo"
			),
			ProductosController::Lista()
		);
		
                function funcion_activo( $activo )
                {
                    return $activo ? "Activo" : "Inactivo";
                }
                
                $tabla->addColRender("activo", "funcion_activo");
                
		$tabla->addOnClick( "id_producto", "(function(a){ window.location = 'productos.ver.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
		
		$page->nextTab("Categorias");
		$page->nextTab("Lista");
		
		$page->render();
