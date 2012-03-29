<?php

define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$tarifa = TarifaDAO::getByPK($_GET["tid"]);

if (is_null($tarifa)) {
    die;
}
//
// Parametros necesarios
// 
$page->requireParam("tid", "GET", "Esta tarifa no existe.");


//
// Titulo de la pagina
// 
$page->addComponent(new TitleComponent( "Editar Tarifa"));

$page->addComponent(new TitleComponent( utf8_decode($tarifa->getNombre()), 2 ) );


//
// Forma de producto
// 

$form = new DAOFormComponent($tarifa);        

$form->createComboBoxJoin("id_moneda", "nombre",MonedaDAO::getAll(), $tarifa->getIdMoneda());
$form->createComboBoxJoin("id_version_activa", "nombre",TarifaDAO::search(new Tarifa( array("id_tarifa" => $tarifa->getIdTarifa()) )), $tarifa->getIdVersionActiva());

$form->sendHidden("id_tarifa");

$form->addApiCall("api/tarifa/editar/");
$form->onApiCallSuccessRedirect("tarifas.ver.php?tid={$_GET['tid']}");






//$form->createComboBoxJoin("id_rol", "nombre", RolDAO::getAll(), $tarifa->getIdRol());
//$form->createComboBoxJoin("id_moneda", "nombre", MonedaDAO::getAll(), $tarifa->getIdMoneda());
//$form->createComboBoxJoin("id_clasificacion_cliente", "nombre", ClasificacionClienteDAO::getAll(), $tarifa->getIdClasificacionCliente());
//$form->createComboBoxJoin("id_clasificacion_proveedor", "nombre", ClasificacionProveedorDAO::getAll(), $tarifa->getIdClasificacionProveedor());
//$form->createComboBoxJoin("id_sucursal", "nombre", SucursalDAO::getAll(), $tarifa->getIdSucursal());
//$form->createComboBoxJoinDistintName("id_tarifa_venta", "id_tarifa", "nombre", TarifaDAO::search(new Tarifa(array("id_tarifa" => $tarifa->getIdTarifaVenta()))));
//$form->createComboBoxJoin("id_tarifa_compra", "nombre", TarifaDAO::search(new Tarifa(array("id_tarifa" => $tarifa->getIdTarifaCompra()))));

$page->addComponent($form);


$page->render();
