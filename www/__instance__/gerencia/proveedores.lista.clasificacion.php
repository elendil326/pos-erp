<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
                
                $page->addComponent( new TitleComponent( "Clasificaciones de proveedor" ) );
		$page->addComponent( new MessageComponent( "Lista de clasificaciones de proveedor" ) );
		
		$tabla = new TableComponent( 
			array(
                                "nombre" => "Nombre",
                                "descripcion" => "Descripcion",
                                "activa" => "Activa"
			),
			ProveedoresController::ListaClasificacion()
		);
                
                function funcion_activa( $activa )
                {
                    return ($activa) ? "Activa" : "Inactiva";
                }
                
                $tabla->addColRender("activa", "funcion_activa");
                
		$tabla->addOnClick( "id_clasificacion_proveedor", "(function(a){ window.location = 'proveedores.clasificacion.ver.php?cid=' + a; })" );
		
			
		$page->addComponent( $tabla );
                
		$page->render();
