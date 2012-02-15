<?php


	define("BYPASS_INSTANCE_CHECK", true);
	
	require_once("../server/bootstrap.php");
	
	$page = new PosComponentPage(   );
	
	$page->addComponent(new FreeHtmlComponent( "<div class=\"POS Boton\"><a href='trial'>Crear una cuenta</a></div>" ));
	$page->addComponent(new FreeHtmlComponent( "<div class=\"POS Boton\"><a href='front_ends/j/'>Desarrolladores</a></div>" ));

	$page->render(   );