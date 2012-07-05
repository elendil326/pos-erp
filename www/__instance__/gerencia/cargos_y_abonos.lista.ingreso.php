<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

		$page = new GerenciaComponentPage();
		
		$page->addComponent( new TitleComponent( "Ingresos" ) );
		$page->addComponent( new MessageComponent( "Lista de ingresos " ) );
		
		$lista = CargosYAbonosController::ListaIngreso();

		$tabla = new TableComponent( 
			array(
				"id_ingreso"			=> "id_ingreso",
				"id_empresa"	 		=> "id_empresa",
				"id_usuario"			=> "id_usuario",
				"id_concepto_ingreso"	=> "concpto",
				"fecha_del_ingreso"		=> "fecha",
				"monto"					=> "monto"
			),
			$lista["resultados"]
		);

		function funcion_cancelado($cancelado){
            return ($cancelado ? "Cancelado" : "Activo" );
        }
		
		$tabla->addColRender("cancelado", "funcion_cancelado");
		
		$tabla->addOnClick( "id_ingreso", "(function(a){ window.location = 'cargos_y_abonos.lista.ingreso.php'; })" );
		
		$page->addComponent( $tabla );
		
		$page->render();
