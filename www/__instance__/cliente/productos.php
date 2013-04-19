<?php
require_once("../../../server/bootstrap.php");

$page = new ClienteComponentPage();
$page->addComponent(new TitleComponent("Productos"));

// sacar las propiedades indicadas
$campos = ConfiguracionDAO::Propiedades();
$columns = array();
foreach ($campos as $key => $value) {
	if ($value == "foto_del_producto") {
		unset($campos[$key]);
	} else {
    	$columns[$value] = ucwords(str_replace('_', ' ', $value));
    }
}

// sacar los productos correspondientes
function filter($product)
{
    return $product->getVisibleEnVc();
}
$products = array_filter(ProductosController::Lista(), "filter");

// dar formato a los campos como corresponda
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

$table = new TableComponent($columns, $products);
$table->addOnClick("id_producto", "(function(a) {window.location = 'productos.ver.php?pid=' + a;})");

$page->addComponent($table);

$page->render();