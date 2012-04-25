<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	// Parametros necesarios
	// 
	$page->requireParam("did", "GET", "Esta direccion no existe.");
	$page->requireParam("cid", "GET", "Esta usuario no existe.");
	$esta_dir = DireccionDAO::getByPK($_GET["did"]);

	//titulos
	$page->addComponent(new TitleComponent("Editar direccion: " . $esta_dir->getColonia()));

	//forma de nuevo cliente

	$form = new DAOFormComponent($esta_dir);

	$form->hideField(array(
	    "id_direccion",
	    "id_usuario_ultima_modificacion",
	    "ultima_modificacion"
	));
	
	$form->sendHidden("id_direccion");

	$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_dir->getIdCiudad());

	$form->addApiCall("api/cliente/editar/");
	$form->beforeSend("editar_direccion");
	
	$page->partialRender();

	?>	

	<script>
		var cliente = <?php echo $_GET["cid"] ?> ;
		function editar_direccion(obj){		
			return	{	
				id_cliente		: cliente,
				direcciones 		: Ext.JSON.encode([obj]) 
			}
		}
	</script>

	<?php
	$form->onApiCallSuccessRedirect("clientes.ver.php?cid=".$_GET["cid"]."");

	$page->addComponent($form);


	//render the page
	$page->render();
