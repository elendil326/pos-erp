<?php



define("BYPASS_INSTANCE_CHECK", false);

require_once("../../../server/bootstrap.php");

$page = new ClienteComponentPage();

$page->render();


