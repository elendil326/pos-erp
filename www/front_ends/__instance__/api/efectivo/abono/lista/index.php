<?php
/**
  * GET api/efectivo/abono/lista
  * Lista los abonos
  *
  * Lista los abonos, puede filtrarse por empresa, por sucursal, por caja, por usuario que abona y puede ordenarse segun sus atributos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.abono.lista.php");

$api = new ApiEfectivoAbonoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
