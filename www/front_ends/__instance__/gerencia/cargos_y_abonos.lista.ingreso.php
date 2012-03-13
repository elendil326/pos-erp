<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$page->addComponent( new TitleComponent( "Ingresos" ) );
		$page->addComponent( new MessageComponent( "Lista de ingresos " ) );
		
		$lista = CargosYAbonosController::ListaIngreso();
		
		$tabla = new TableComponent( 
			array(
				"cancelado"			=> "Cancelado",
				"monto_minimo" 		=> "Monto Minidmo",
				"monto_maximo"      => "Monto Maximo"
			),
			$lista
		);
		var_dump($lista);
		function funcion_cancelado($cancelado){
            return ($cancelado ? "Cancelado" : "Activo" );
        }
		
		$tabla->addColRender("cancelado", "funcion_cancelado");
		$tabla->addOnClick( "id_ingreso", "(function(a){ window.location = 'cargos_y_abonos.lista.ingreso.php'; })" );

		$page->render();
