<?php
require_once("../../../server/bootstrap.php");

$page = new ClienteTabPage();
$page->nextTab("General");
$page->requireParam("pid", "GET", "Este producto no existe.");
$producto = ProductoDAO::getByPK($_GET["pid"]);

if (is_null($producto)) {
	$page->render();
	exit;
}

$form = new DAOFormComponent($producto);
$form->setEditable(false);

$campos_escondidos = array_diff(
	array_keys(get_class_vars('Producto')), ConfiguracionDAO::Propiedades()
);

$form->hideField($campos_escondidos);

$page->addComponent($form);
$page->render();