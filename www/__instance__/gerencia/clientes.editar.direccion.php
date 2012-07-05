<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	// Parametros necesarios
	// 

	
	$page->requireParam("cid", "GET", "Esta usuario no existe.");
	
	
	$this_client = UsuarioDAO::getByPK( $_GET["cid"] );
	
	if(is_null($this_client->getIdDireccion())){
		
		//no existe direccion
		Logger::log("El uid=" . $_GET["cid"] . " no tiene direccion. Insertando." );
		
		DireccionController::NuevaDireccionParaUsuario(  $_GET["cid"] );
		
		//recargar el objeto de cliente
		$this_client = UsuarioDAO::getByPK( $_GET["cid"] );
	}
	

	
	$esta_dir = DireccionDAO::getByPK($this_client->getIdDireccion());
	
	
	if(is_null($esta_dir)){
		//esta definida pero el registro no existe por alguna razon
		Logger::error("user " . $_GET["cid"] . " se supone que tiene id direccion = " .$this_client->getIdDireccion()   . " , pero esta en null ...");
		
		DAO::transBegin();
		
		$this_client->setIdDireccion(NULL);
		
        try{
            UsuarioDAO::save($this_client);

			DireccionController::NuevaDireccionParaUsuario(  $this_client->getIdUsuario() );
			
			//recargar el objeto de cliente
			$this_client = UsuarioDAO::getByPK( $_GET["cid"] );
			
        } catch(Exception $e) {
            DAO::transRollback();
            throw new Exception("No se pudo crear la direccion: ".$e);

        }

        DAO::transEnd();
		
	}
	
	
	
	
	
	$esta_dir = DireccionDAO::getByPK($this_client->getIdDireccion());
	

	
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
	$form->setCaption("id_ciudad", "Ciudad");

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
