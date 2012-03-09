<?php


	define("BYPASS_INSTANCE_CHECK", true);
	
	require_once("../server/bootstrap.php");
	
	$page = new PosComponentPage(   );
	
	//$page->addComponent("<div class=\"POS Boton\"><a href='trial'>Crear una cuenta</a></div>" );
	//$page->addComponent("<div class=\"POS Boton\"><a href='front_ends/j/'>Desarrolladores</a></div>" );

	$page->addComponent("<h1>Caffeina POS</h1>" );
	$page->addComponent("<h3 style='margin-top: 0px;'>Enterprise Resource Planning</h3>" );
	$page->addComponent("<img src=\"media/main_site/oferta.png\">" );


	$page->partialRender(   );	

	?>


	<br><hr><br>


	<!--<img src="media/tab_a.png">
	<img src="media/tab_b.png">-->

	<?php

	$page->render(   );