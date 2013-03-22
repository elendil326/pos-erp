<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->addComponent( new TitleComponent( "Inventario" ) );

		$page->nextTab("Estructura");

		$page->nextTab("Fisico");

		$page->render();
