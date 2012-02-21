<?php 



		define("BYPASS_INSTANCE_CHECK", false);

		require_once("../../../../server/bootstrap.php");

		$page = new GerenciaTabPage();

		$page->nextTab("tab1");

		$page->addComponent("hola, estoy dentro de tab1");
		$page->addComponent("hola, yo tambien estoy dentro de tab1<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");

		$page->nextTab("tab2");		

		$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");
		$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");

		$page->nextTab("tab3");		

		$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
		$page->addComponent("hola, yo tambien estoy dentro de tab2<br><br><br><br><br><br><br>");
		$page->addComponent("hola, estoy dentro de tab2<br><br><br>");
	
		$page->render();





