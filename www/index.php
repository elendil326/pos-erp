<?php

	if(!class_exists("PHPUnit_Runner_Version")){
		define("BYPASS_INSTANCE_CHECK", true);
		require_once("../server/bootstrap.php");
	}
		
	
	$page = new PosComponentPage(   );
	
	
	$page->partialRender(   );	

	?>
	
	<h1>Caffeina POS</h1>
	<h3 style='margin-top: 0px;'>Enterprise Resource Planning</h3>
	<img src="media/main_site/oferta.png">


	
	<?php
	

	$page->render(   );