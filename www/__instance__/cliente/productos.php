<?php
require_once("../../../server/bootstrap.php");

$page = new ClienteTabPage();
$page->addComponent(new TitleComponent("Productos"));
$page->nextTab("Lista");
$columns = array(
    "codigo_producto" => "Código"
);

$products = ProductosController::Lista();
function filter($product)
{
    return $product->getVisibleEnVc();
}
$table = new TableComponent($columns, array_filter($products, "filter"));
$page->addComponent($table);

$page->render();
?>