<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
/*
                $page->addComponent( new TitleComponent( "Unidades" ) );
		$page->addComponent( new MessageComponent( "Lista unidades" ) );
		
		$tabla = new TableComponent( 
			array(
				"nombre" => "Nombre",
                                "descripcion" => "Descripcion",
                                "es_entero" => "Tipo de manejo",
                                "activa" => "Activa"
			),
			ProductosController::ListaUnidad()
		);
		
                function funcion_activa( $activa )
                {
                    return $activa ? "Activa" : "Inactiva";
                }
                
                function funcion_es_entero( $es_entero )
                {
                    return $es_entero ? "Entero" : "Decimal";
                }
                
                $tabla->addColRender("activa", "funcion_activa");
                $tabla->addColRender("es_entero", "funcion_es_entero");
                
		$tabla->addOnClick( "id_unidad", "(function(a){ window.location = 'productos.unidad.ver.php?uid=' + a; })" );
		*/
			
//		$page->addComponent( $tabla );
                
		$page->render();
