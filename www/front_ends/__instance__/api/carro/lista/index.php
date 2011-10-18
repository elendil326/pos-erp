<?php
/**
  * GET api/carro/lista
  * Lista de carros en la empresa
  *
  * Lista todos los carros de la instancia. Puede filtrarse por empresa, por su estado y ordenarse por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.lista.php");

$api = new ApiCarroLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
