<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaComponentPage();

		$page->addComponent(new TitleComponent("Lista de impuestos"));

		$i = ImpuestosController::Lista();
		
		$table = new TableComponent( array(
				"nombre" => "nombre",
				"descripcion" => "descripcion",
				"monto_porcentaje" => "porcentaje"
			), $i["resultados"] );

		$page->addComponent($table);
		
		$page->render();
