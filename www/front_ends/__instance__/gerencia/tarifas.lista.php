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


$page->addComponent($tabla);

$page->render();
