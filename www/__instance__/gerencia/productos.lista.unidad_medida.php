<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Unidades de Medida" ) );
		$page->addComponent( new MessageComponent( "Lista Unidades de Medida" ) );
		
		$tabla = new TableComponent( 
			array(	
				"id_categoria_unidad_medida" => "Categoria Unidad Medida",		
            	"descripcion" => "Descripcion",
                "abreviacion" => "Abreviacion",
                "tipo_unidad_medida" => "Tipo Unidad Medida",
				"factor_conversion" => "Factor de Conversion",
				"activa" => "Activa"
			),
			UnidadMedidaDAO::getAll()
		);
		
                function funcion_activa( $activa )
                {
                    return $activa ? "Activa" : "Inactiva";
                } 
			
				function funcion_categoria_unidad_medida($id_categoria_unidad_medida)
				{
					$cat = CategoriaUnidadMedidaDAO::getByPK($id_categoria_unidad_medida);
					return $cat->getDescripcion();
				}                              
                
                $tabla->addColRender("activa", "funcion_activa");
                $tabla->addColRender("id_categoria_unidad_medida", "funcion_categoria_unidad_medida");

		$tabla->addOnClick( "id_unidad_medida", "(function(a){ window.location = 'productos.unidad_medida.ver.php?umid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
