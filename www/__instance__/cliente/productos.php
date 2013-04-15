<?php
require_once("../../../server/bootstrap.php");

$page = new ClienteTabPage();
$page->addComponent(new TitleComponent("Productos"));
$page->nextTab("Lista");

$campos = ConfiguracionDAO::Propiedades();
$columns = array();
foreach ($campos as $value)
{
    $columns[$value] = ucwords(str_replace('_', ' ', $value));
}
$products = ProductosController::Lista();

function filter($product)
{
    return $product->getVisibleEnVc();
}

$table = new TableComponent($columns, array_filter($products, "filter"));

$page->addComponent($table);

$page->render();