<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	// Parametros necesarios
	// 
	$page->requireParam("cid", "GET", "Este cliente no existe.");
	$este_cliente = UsuarioDAO::getByPK($_GET["cid"]);

	//titulos
	$page->addComponent(new TitleComponent("Editar cliente: " . $este_cliente->getNombre()));

	//forma de nuevo cliente

	$form = new DAOFormComponent($este_cliente);

	$form->hideField(array(
	    "id_usuario",
	    "id_rol",
	    "id_clasificacion_proveedor",
	    "fecha_asignacion_rol",
	    "comision_ventas",
	    "fecha_alta",
	    "fecha_baja",
	    "activo",
	    "last_login",
	    "salario",
	    "dias_de_embarque",
	    "consignatario",
	    "tiempo_entrega",
	    "cuenta_bancaria"
	));

	$form->renameField(array(
	    "id_usuario" => "id_cliente"
	));
	$form->sendHidden("id_cliente");

	$form->setValueField("password", "");


	$form->createComboBoxJoin("id_moneda", "nombre", MonedaDAO::search(new Moneda(array(
	    "activa" => 1
	))), $este_cliente->getIdMoneda());
	
	$form->createComboBoxJoin("id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $este_cliente->getIdClasificacionCliente());
	$form->createComboBoxJoin("id_sucursal", "razon_social", SucursalDAO::search(new Sucursal(array(
	    "activa" => 1
	))), $este_cliente->getIdSucursal());

	$form->addApiCall("api/cliente/editar/");
	$form->onApiCallSuccessRedirect("clientes.lista.php");



	$form->renameField(array(
	    "nombre" => "razon_social",
	    "codigo_usuario" => "codigo_cliente",
	    "correo_electronico" => "email",
	    "id_clasificacion_cliente" => "clasificacion_cliente",
	    "id_moneda" => "moneda_del_cliente",
	    "pagina_web" => "sitio_web",
	    "id_sucursal" => "sucursal"
	));

	$page->addComponent($form);


	//render the page
	$page->render();
