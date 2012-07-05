<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

                $page->addComponent( new TitleComponent( "Paquetes" ) );
		$page->addComponent( new MessageComponent( "Lista de paquetes " ) );
		
		$tabla = new TableComponent( 
			array(
                                "nombre" => "Nombre",
                                "margen_utilidad" => "Margen_utilidad",
                                "precio"    => "Precio",
                                "descuento" => "Descuento",
                                "foto_paquete" => "Foto de paquete",
                                "activo" => "Activo"
			),
			PaquetesController::Lista()
		);
                
                function funcion_activo( $activo )
                {
                    return ($activo) ? "Activo" : "Inactivo";
                }
                
                $tabla->addColRender("activo", "funcion_activo");
                
		$tabla->addOnClick( "id_paquete", "(function(a){ window.location = 'paquetes.detalle.php?pid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
