<?php 
		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Impuestos" ) );

		$tabla = new TableComponent( 
			array(
                                "id_impuesto"=>"ID",
                                "nombre" => "Nombre",
				"descripcion" => "Descripcion",
                                "codigo" => "Codigo",
				"importe" => "Importe",
                                "tipo"=>"Tipo",
                                "aplica"=>"Aplica",
                                "incluido"=>"Incluido",
                                "activo"=>"Activo"
			),
                        //ImpuestosYRetencionesController::ListaImpuesto()
                        ImpuestoDAO::getAll()
		);
		
                function funcion_es_monto($es_monto)
                {
                    return ($es_monto? "Si" : "No");
                }
                
                $tabla->addColRender("es_monto", "funcion_es_monto");
                
		$tabla->addOnClick( "id_impuesto", "(function(a){window.location = 'impuestos.impuesto.ver.php?iid='+a;})" );
		
		$page->addComponent( $tabla );
                
		$page->render();
