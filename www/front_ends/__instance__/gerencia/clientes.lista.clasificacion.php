<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Clasificaciones de cliente" ) );
		$page->addComponent( new MessageComponent( "Lista de clasificaciones de cliente " ) );
		
		$tabla = new TableComponent( 
			array(
                                "clave_interna" => "Clave interna",
                                "nombre" => "Nombre",
                                "descripcion" => "Descripcion",
                                "margen_utilidad"    => "Margen de utilidad",
                                "descuento" => "Descuento"
			),
			ClientesController::ListaClasificacion()
		);
                
//                function funcion_activo( $activo )
//                {
//                    return ($activo) ? "Activo" : "Inactivo";
//                }
//                
//                $tabla->addColRender("activo", "funcion_activo");
                
		$tabla->addOnClick( "id_clasificacion_cliente", "(function(a){ window.location = 'cliente.clasificacion.ver.php?cid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
