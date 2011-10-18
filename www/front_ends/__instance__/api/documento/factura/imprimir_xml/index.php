<?php
/**
  * GET api/documento/factura/imprimir_xml
  * Imprime el xml de una factura
  *
  * Imprime el xml de una factura.

Update : No se si este metodo tenga una utilidad real, ya que cuando se recibe el XML timbrado, se crea el archivo .xml y en el unico momento que se vuelve a ocupar es para enviarlo por correo al cliente.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.documento.factura.imprimir_xml.php");

$api = new ApiDocumentoFacturaImprimir_xml();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
