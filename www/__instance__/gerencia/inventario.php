<?php 

		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->nextTab("Estructura");

		$page->nextTab("Fisico");

		$page->render();
