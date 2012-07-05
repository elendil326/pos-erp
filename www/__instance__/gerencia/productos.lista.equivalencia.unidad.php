<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Equivalencias entre unidades" ) );
		$page->addComponent( new MessageComponent( "Lista de equivalencias entre unidades" ) );
		
		$tabla = new TableComponent( 
			array(
				"id_unidad" => "Una unidad de",
                                "equivalencia" => "Equivale a ",
                                "id_unidades" => "Unidades"
			),
			ProductosController::Lista_equivalenciaUnidad()
		);
		
                function funcion_unidad( $unidad )
                {
                    return UnidadDAO::getByPK($unidad) ? UnidadDAO::getByPK($unidad)->getNombre() : "????";
                }
                
                $tabla->addColRender("id_unidad", "funcion_unidad");
                $tabla->addColRender("id_unidades", "funcion_unidad");
                
		$tabla->addOnClick( "id_unidad", "(function(a){ window.location = 'productos.unidad.ver.php?uid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
