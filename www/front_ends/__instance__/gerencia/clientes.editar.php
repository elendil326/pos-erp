<?php

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	// Parametros necesarios
	// 
	$page->requireParam("cid", "GET", "Este cliente no existe.");
	$este_cliente = UsuarioDAO::getByPK($_GET["cid"]);

	//titulos
	$page->addComponent(new TitleComponent("Editando a " . $este_cliente->getNombre(), 2));

	//forma de nuevo cliente

	$form = new DAOFormComponent($este_cliente);

	$form->hideField(array(
	    "id_usuario",
	    "id_rol",
	    "id_clasificacion_proveedor",
		"id_direccion",
		"id_direccion_alterna",
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
	    "cuenta_bancaria",
		"mensajeria",
		"token_recuperacion_pass",
		"ventas_a_credito",
		"dia_de_pago",
		"dia_de_revision",
		
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
	$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa" ,"nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"venta"))));
	$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("tipo_tarifa"=>"compra"))));
	$form->createComboBoxJoin( "tarifa_compra_obtenida", "tarifa_compra_obtenida", array("rol", "proveedor", "cliente","usuario") );
	$form->createComboBoxJoin( "tarifa_venta_obtenida", "tarifa_venta_obtenida", array("rol", "proveedor", "cliente","usuario") );

	$form->addApiCall("api/cliente/editar/", "POST");
	$form->onApiCallSuccessRedirect("clientes.ver.php?cid=" . $_GET["cid"]);



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


	$page->addComponent("<hr>");


	//buscar los parametros extra
	$out = ExtraParamsValoresDAO::getVals("clientes", $este_cliente->getIdUsuario());

	$epform = new FormComponent();
	$epform->setEditable(true);
	

	foreach($out as $ep){
		$epform->addField($ep["campo"], $ep["caption"], $ep["tipo"], $ep["val"]);
		if(!is_null($ep["descripcion"])){
			$epform->setHelp($ep["campo"], $ep["descripcion"]);	
		}
		
	}
	

	$page->addComponent($epform);


	//render the page
	$page->render();
