<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	$page = new GerenciaComponentPage(  );
	
	$page->partialRender();
	?>
		<script>
		function doDeactivate(){
			
		}
		</script>
	
	<?php
	
	$page->requireParam("cid", "GET", "Este cliente no existe");
	
	$page->addComponent("<h2>Desactivar a " . "</h2>");
	
	
	
	$page->addComponent("<div class=\"POS Boton\" onClick=\"doDeactivate()\">Desactivarlo</div>");
	
	$page->render();