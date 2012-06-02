<?php 

	define("BYPASS_INSTANCE_CHECK", false);

	require_once("../../../../server/bootstrap.php");

	$page = new GerenciaComponentPage();

	$page->requireParam(  "oid", "GET", "Esta orden de servicio no existe." );

	$esta_orden = OrdenDeServicioDAO::getByPK( $_GET["oid"] );

	//titulos
	$page->addComponent( new TitleComponent( "Terminar Orden " ) );

	$v = new Venta();
	$v->setEsCotizacion(0);

	$form = new DAOFormComponent(  $v  );


	$form->addField("id_orden", "id_orden", "text", $_GET["oid"]);

	$form->hideField( array( 
		"id_orden",
		"total",
		"id_venta",
		"id_caja",
		"saldo",
		"es_cotizacion",
		"id_venta_caja",    
		"id_comprador_venta",
		"fecha",
		"subtotal",
		"impuesto",
		"id_sucursal",
		"id_usuario",
		"cancelada",
		"retencion",
		"descuento"
	));

	$form->renameField(array( "tipo_de_venta" => "tipo_venta" ));

	$form->sendHidden(array( "id_orden", "es_cotizacion" ));

	$form->addApiCall( "api/servicios/orden/terminar/");

	$form->onApiCallSuccessRedirect( "servicios.php" );

	$form->makeObligatory(array( 
		"tipo_venta"
	));

	$form->createComboBoxJoin("tipo_venta", "tipo_venta", array( "credito" , "contado" ));

	$form->createComboBoxJoin("tipo_de_pago", "tipo_de_pago", array( "cheque" , "tarjeta", "efectivo" ), "efectivo");

	$page->addComponent($form);

	$page->render();
