<?php
/**
  * GET api/documento/factura/imprimir
  * Imprime una factura
  *
  * Imprime una factura
Update : La respuesta solo deber?a de contener success :true | false, y en caso de error, su descripcion, no se necesita apra anda en el JSON de respuesta una propiedad factura.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.documento.factura.imprimir.php");

$api = new ApiDocumentoFacturaImprimir();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
