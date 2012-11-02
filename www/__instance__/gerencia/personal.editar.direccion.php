<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../server//bootstrap.php");

	$page = new GerenciaComponentPage();

	// Parametros necesarios
	// 

	
	$page->requireParam("uid", "GET", "Esta usuario no existe.");
	
	
	$this_personal = UsuarioDAO::getByPK( $_GET["uid"] );
	
	if(is_null($this_personal->getIdDireccion())){
		
		//no existe direccion
		Logger::log("El uid=" . $_GET["uid"] . " no tiene direccion. Insertando." );
		
		DireccionController::NuevaDireccionParaUsuario(  $_GET["uid"] );
		
		//recargar el objeto de cliente
		$this_personal = UsuarioDAO::getByPK( $_GET["uid"] );
	}
	

	
	$esta_dir = DireccionDAO::getByPK($this_personal->getIdDireccion());
	
	
	if(is_null($esta_dir)){
		//esta definida pero el registro no existe por alguna razon
		Logger::error("user " . $_GET["uid"] . " se supone que tiene id direccion = " .$this_personal->getIdDireccion()   . " , pero esta en null ...");
		
		DAO::transBegin();
		
		$this_personal->setIdDireccion(NULL);
		
        try{
            UsuarioDAO::save($this_personal);

			DireccionController::NuevaDireccionParaUsuario(  $this_personal->getIdUsuario() );
			
			//recargar el objeto de cliente
			$this_personal = UsuarioDAO::getByPK( $_GET["uid"] );
			
        } catch(Exception $e) {
            DAO::transRollback();
            throw new Exception("No se pudo crear la direccion: ".$e);

        }

        DAO::transEnd();
		
	}
	
	
	
	
	
	$esta_dir = DireccionDAO::getByPK($this_personal->getIdDireccion());
	

	
	//titulos
	$page->addComponent(new TitleComponent("Editar direccion: " . $esta_dir->getColonia()));

	//forma de nuevo usuario

	$form = new DAOFormComponent($esta_dir);

	$form->hideField(array(
	    "id_direccion",
	    "id_usuario_ultima_modificacion",
	    "ultima_modificacion"
	));
	
	$form->sendHidden("id_direccion");

	$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_dir->getIdCiudad());
	$form->setCaption("id_ciudad", "Ciudad");

	$form->addApiCall("api/personal/usuario/editar/");
	$form->beforeSend("editar_direccion");
	
	$page->partialRender();

	?>	

	<script>
		var usuario = <?php echo $_GET["uid"] ?> ;
		function editar_direccion(obj){		
			return	{	
				id_usuario		: usuario,
				direcciones 		: Ext.JSON.encode([obj]) 
			}
		}
	</script>

	<?php
	$form->onApiCallSuccessRedirect("personal.usuario.ver.php?uid=".$_GET["uid"]."");

	$page->addComponent($form);


	//render the page
	$page->render();
