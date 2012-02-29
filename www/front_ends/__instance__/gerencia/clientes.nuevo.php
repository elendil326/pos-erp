<?php



	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	//titulos
	$page->addComponent(new TitleComponent("Nuevo cliente"));

	//forma de nuevo cliente
	$form = new DAOFormComponent(array(
	    new Usuario(),
	    new Direccion()
	));

	$form->hideField(array(
	    "id_usuario",
	    "id_sucursal",
	    "id_rol",
	    "id_clasificacion_proveedor",
	    "fecha_asignacion_rol",
	    "comision_ventas",
	    "fecha_alta",
	    "fecha_baja",
	    "activo",
	    "last_login",
	    "salario",
	    "saldo_del_ejercicio",
	    "dia_de_pago",
	    "dias_de_embarque",
	    "id_direccion",
	    "ultima_modificacion",
	    "id_usuario_ultima_modificacion",
	    "consignatario",
	    "facturar_a_terceros",
	    "dia_de_revision",
	    "tiempo_entrega",
	    "ventas_a_credito",
	    "intereses_moratorios",
	    "cuenta_bancaria",
	    "dias_de_credito"
	));

	$form->createComboBoxJoin("id_moneda", "nombre", MonedaDAO::search(new Moneda(array(
	    "activa" => 1
	))));
	$form->createComboBoxJoin("id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll());

	$form->renameField(array(
	    "nombre" => "razon_social",
	    "codigo_usuario" => "codigo_cliente",
	    "telefono" => "telefono1",
	    "correo_electronico" => "email",
	    "id_clasificacion_cliente" => "clasificacion_cliente",
	    "id_moneda" => "moneda_del_cliente",
	    "pagina_web" => "direccion_web"
	));

	$form->addApiCall("api/cliente/nuevo/");
	//$form->onApiCallSuccessRedirect("clientes.lista.php");

	$form->makeObligatory(array(
	    "razon_social"
	));

	$form->createComboBoxJoin("id_ciudad", "nombre", CiudadDAO::getAll());

	$page->addComponent($form);


	//render the page
	$page->render();
