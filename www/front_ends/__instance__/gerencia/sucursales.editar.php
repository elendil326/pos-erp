<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//
	// Parametros necesarios
	// 
	$page->requireParam("sid", "GET", "Esta sucursal no existe.");
	$esta_sucursal  = SucursalDAO::getByPK($_GET["sid"]);
	$esta_direccion = DireccionDAO::getByPK($esta_sucursal->getIdDireccion());
	
	
	//
	// Titulo de la pagina
	// 
	$page->addComponent(new TitleComponent("Editar sucursal " . $esta_sucursal->getRazonSocial(), 2));

	//
	// Forma de usuario
	// 
	$form = new DAOFormComponent(array(
	    $esta_sucursal,
	    $esta_direccion
	));
	$form->hideField(array(
	    "id_sucursal",
	    "id_direccion",
	    "fecha_apertura",
	    "fecha_baja",
	    "id_direccion",
	    "ultima_modificacion",
	    "id_usuario_ultima_modificacion",
	    "referencia",
	    "activa"
	));
	$form->sendHidden("id_sucursal");

	$form->addApiCall("api/sucursal/editar/", "GET");
	$form->onApiCallSuccessRedirect("sucursales.lista.php");


	$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll(), $esta_direccion->getIdCiudad());

	$form->createComboBoxJoinDistintName("id_gerente", "id_usuario", "nombre", UsuarioDAO::search(new Usuario(array(
	    "id_rol" => 2
	))), $esta_sucursal->getIdGerente());


	$form->renameField(array(
	    "id_ciudad" => "municipio"
	));

	$page->addComponent($form);

	$page->render();
