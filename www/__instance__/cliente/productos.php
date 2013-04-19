<?php
require_once("../../../server/bootstrap.php");

$page = new ClienteTabPage();
$page->addComponent(new TitleComponent("Productos"));
$page->nextTab("Lista");

$campos = ConfiguracionDAO::Propiedades();
$columns = array();
foreach ($campos as $value) {
    $columns[$value] = ucwords(str_replace('_', ' ', $value));
}

$products = array_filter(ProductosController::Lista(), "filter");

foreach ($products as $product) {
	$product->setPrecio(FormatMoney($product->getPrecio()));
	$product->setCostoEstandar(FormatMoney($product->getCostoEstandar()));
	$product->setCostoExtraAlmacen(FormatMoney($product->getCostoExtraAlmacen()));

	if ($product->getCompraEnMostrador()) {
		$product->setCompraEnMostrador("S&iacute;");
	} else {
		$product->setCompraEnMostrador("No");
	}

	$unidad = UnidadMedidaDAO::getByPK($product->getIdUnidadCompra());
	if (!is_null($unidad)) {
		$product->setIdUnidadCompra($unidad->getDescripcion());
	}

	$unidad = UnidadMedidaDAO::getByPK($product->getIdUnidad());
	if (!is_null($unidad)) {
		$product->setIdUnidad($unidad->getDescripcion());
	}
}

function filter($product)
{
    return $product->getVisibleEnVc();
}

$table = new TableComponent($columns, $products);
$table->addOnClick("id_producto", "(function(a) {window.location = 'productos.ver.php?pid=' + a;})");

$page->addComponent($table);

$page->render();