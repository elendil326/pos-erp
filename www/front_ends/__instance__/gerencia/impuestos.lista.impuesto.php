<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Impuestos" ) );

		$tabla = new TableComponent( 
			array(
                                "nombre"=> "Nombre",
				"monto_porcentaje"  => "Monto/Porcentaje",
				"es_monto"  => "Es monto",
				"descripcion" => "Descripcion"
			),
                         ImpuestosYRetencionesController::ListaImpuesto()
		);
		
                function funcion_es_monto($es_monto)
                {
                    return ($es_monto? "Si" : "No");
                }
                
                $tabla->addColRender("es_monto", "funcion_es_monto");
                
		$tabla->addOnClick( "id_impuesto", "(function(a){window.location = 'impuestos.impuesto.ver.php?iid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
