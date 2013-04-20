<?php

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server//bootstrap.php");

		$page = new GerenciaTabPage();
		$controller = new ContabilidadController();
		$lista = $controller::ListarCatalogosCuentas();

		$page->addComponent(new TitleComponent("Catalogos de Cuentas", 2));

		$page->addComponent(sizeof($lista["resultados"]) ." catalogos de Cuentas.");
		
		$tabla = new TableComponent(
										array(
											"descripcion"	=> "Descripcion"
										),
										$lista["resultados"]
									);

		$tabla->convertToExtJs(false);
 		$tabla->addOnClick( "id_catalogo", "(function(a){ window.location = 'contabilidad.cuentas.php?idcc=' + a; })" );

		$page->addComponent( $tabla );

		$page->render();





