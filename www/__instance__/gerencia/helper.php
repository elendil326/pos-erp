<?php


define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../../server/bootstrap.php");

$page = new GerenciaComponentPage();

$page->addComponent(new TitleComponent("Ayuda",1));

//$page->addComponent("<img src=\"../../../media/iconos/1338004933_Help.png\">");



$page->render();
