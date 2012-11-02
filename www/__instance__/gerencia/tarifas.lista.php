<?php

define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

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
    return MonedaDAO::getByPK($id_moneda)->getSimbolo() . " ($)";
}

function getActiva($activa) {
    return $activa == 1 ? "<input type=\"checkbox\" checked disabled>" : "<input type=\"checkbox\" disabled>";
}

$tabla->addColRender("id_moneda", "getNombreMoneda");
$tabla->addColRender("activa", "getActiva");

$tabla->addOnClick( "id_tarifa", "(function(a){ window.location = 'tarifas.ver.php?tid=' + a; })" );


$page->addComponent($tabla);

$page->render();
