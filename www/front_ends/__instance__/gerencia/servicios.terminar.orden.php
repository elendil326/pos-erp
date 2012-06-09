<?php

define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();


//
// Parametros necesarios
// 
$page->requireParam("oid", "GET", "Esta orden de servicio no existe.");

$esta_orden = OrdenDeServicioDAO::getByPK($_GET["oid"]);

if(!$esta_orden->getActiva()){
	$page->addComponent(new TitleComponent("Esta orden ya ha sido cerrada."));
	$page->render();
}


$customer         = UsuarioDAO::getByPK($esta_orden->getIdUsuarioVenta());
$link_to_customer = "<a href='clientes.ver.php?cid=" . $esta_orden->getIdUsuarioVenta() . "'>";
$link_to_customer .= $customer->getNombre();
$link_to_customer .= "</a>";



$page->addComponent(new TitleComponent("Terminar orden de servicio " . $_GET["oid"] . " para " . $link_to_customer, 2));

//desactivarla
$esta_orden->setActiva(0);
$form = new DAOFormComponent($esta_orden);

$form->addField("id_orden", "id_orden", "text", $_GET["oid"]);

$form->setEditable(true);

$form->hideField(array(
	"id_usuario_venta",
	"extra_params",
	"motivo_cancelacion",
	"fecha_entrega",
	"cancelada",
	"adelanto",
	"activa",
	"id_usuario",
	"descripcion",
	"fecha_orden",
	"id_servicio",
	"id_usuario_asignado"
));

$form->setCaption("precio", "Precio final");

$form->sendHidden( array("id_orden", "activa") );

$form->addApiCall( "api/servicios/orden/terminar", "POST" );

//$form->onApiCallSuccessRedirect("servicios.detalle.orden.php?oid=" . $_GET["oid"]);

$page->addComponent($form);




$page->render();


die;


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
