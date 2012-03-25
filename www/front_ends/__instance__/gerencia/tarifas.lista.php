<?php

define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$page->addComponent(new TitleComponent("Tarifas"));
$page->addComponent(new MessageComponent("Lista de tarifas"));

$tabla = new TableComponent(
                array(
                    "nombre" => "Nombre",
                    "tipo_tarifa" => "Tipo tarifa",
                    "id_moneda" => "Moneda",
                    "activa" => "Activa"
                ),
                TarifaDAO::getAll()
);

function getNombreMoneda($id_moneda) {
    return MonedaDAO::getByPK($id_moneda)->getNombre();
}

function getActiva($activa) {
    return $activa == 1 ? "Si" : "No";
}

$tabla->addColRender("id_moneda", "getNombreMoneda");
$tabla->addColRender("activa", "getActiva");


$page->addComponent($tabla);

$page->render();
