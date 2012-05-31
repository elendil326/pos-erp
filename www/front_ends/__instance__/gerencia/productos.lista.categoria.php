<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent( new TitleComponent( "Categorias de Producto" ) );
		$page->addComponent( new MessageComponent( "Lista de categorias de producto" ) );
		
		$tabla = new TableComponent( 
			array(
				"nombre" => "Nombre",
				"id_categoria_padre"	=> "Categoria Padre",
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

				function funcion_id_categoria_padre( $id_categoria_padre )
                {
					if( ! is_numeric($id_categoria_padre) )
						return "";

					$cat = ClasificacionProductoDAO::getByPk($id_categoria_padre);
                    return $cat->getNombre();
                }
                
                $tabla->addColRender("id_categoria_padre", "funcion_id_categoria_padre");
                
		$tabla->addOnClick( "id_clasificacion_producto", "(function(a){ window.location = 'productos.categoria.ver.php?cid=' + a; })" );
		
			
		$page->addComponent( $tabla );

		$page->render();
