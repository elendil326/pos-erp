<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Categorias de Producto" ) );
		$page->addComponent( new MessageComponent( "Lista de categorias de producto" ) );
		
		$tabla = new TableComponent( 
			array(
				"nombre" => "Nombre",
				"garantia"	=> "Garantia",
				"descripcion" 		=> "Descripcion",
				"activa" 			=> "Activa"
			),
                         ClasificacionProductoDAO::getAll()
		);
		
                function funcion_activa( $activa )
                {
                    return $activa ? "Activa" : "Inactiva";
                }
                
                $tabla->addColRender("activa", "funcion_activa");
                
		$tabla->addOnClick( "id_clasificacion_producto", "(function(a){ window.location = 'productos.categoria.ver.php?cid=' + a; })" );
		
			
		$page->addComponent( $tabla );

		$page->render();
