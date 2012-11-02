<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	$page = new GerenciaComponentPage(  );
	
	$page->partialRender();
	?>
		<script>
		function doDeactivate(){
			//POS.API.POST("api/cliente/editar",{id_cliente:4813},{callback:function(){}});			
		}
		</script>
	
	<?php
	
	$page->requireParam("cid", "GET", "Este cliente no existe");
	
	$page->addComponent("<h2>Desactivar a " . "</h2>");
	
	
	
	$page->addComponent("<div class=\"POS Boton\" onClick=\"doDeactivate()\">Desactivarlo</div>");
	
	$page->render();