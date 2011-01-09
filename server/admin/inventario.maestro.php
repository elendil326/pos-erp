<?php


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");

require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');

?>

<h1>Inventario Maestro</h1>

<?php



$iMaestro = listarInventarioMaestro() ;

$header = array(
	"folio" 	=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 	=> "Variedad",
	"arpillas"		=> "Arpillas",
	"peso_por_arpilla"		=> "Kg/Arpilla",
	"productor"		=> "Productor",
	"fecha"			=> "Llegada",
	//"transporte"	=> "Transporte",
	"merma_por_arpilla"	=> "Merma",
	"sitio_descarga_desc"=> "Sitio de descarga",
	"existencias"	=> "Existencias",
	"existencias_procesadas"	=> "Limpias" );
	
$tabla = new Tabla( $header, $iMaestro );
$tabla->render();

?>

