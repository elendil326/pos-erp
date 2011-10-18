<?php
/**
  * GET api/pos/probar_conexion
  * Envia una peticion al servidor para probar la conexion
  *
  * Cuando un cliente pierde comunicacion se lanzan peticiones a intervalos pequenos de tiempo para revisar conectividad. Esos requests deberan hacerse a este metodo para que el servidor se de cuenta de que el cliente perdio conectvidad y tome medidas aparte como llenar estadistica de conectividad, ademas esto asegurara que el cliente puede enviar cambios ( compras, ventas, nuevos clientes ) de regreso al servidor. 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.pos.probar_conexion.php");

$api = new ApiPosProbar_conexion();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
