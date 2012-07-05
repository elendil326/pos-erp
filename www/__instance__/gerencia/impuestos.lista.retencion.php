<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Retenciones" ) );

		$tabla = new TableComponent( 
			array(
                                "nombre"=> "Nombre",
				"monto_porcentaje"  => "Monto/Porcentaje",
				"es_monto"  => "Es monto",
				"descripcion" => "Descripcion"
			),
                         ImpuestosYRetencionesController::ListaRetencion()
		);
		
                function funcion_es_monto($es_monto)
                {
                    return ($es_monto? "Si" : "No");
                }
                
                $tabla->addColRender("es_monto", "funcion_es_monto");
                
		$tabla->addOnClick( "id_retencion", "(function(a){window.location = 'impuestos.retencion.ver.php?rid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
