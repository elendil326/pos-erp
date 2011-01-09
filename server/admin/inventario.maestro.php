<?php


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");

require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');

?>

<h1>Inventario Maestro</h1>

<?php


$header = array(
	"remision" 	=> "Numero de remision",
	"descripcion" 	=> "Producto",
	"variedad" 	 	=> "Variedad",
	"arpillas"		=> "Arpillas",
	"promedio"		=> "Kg/Arpilla",
	"Productor"		=> "Productor",
	"fecha"			=> "Fecha llegada",
	"transporte"	=> "Transporte",
	"merma_arpilla"	=> "Merma por arpilla",
	"descarga"		=> "Sitio de descarga",
	"existencias"	=> "Existencias");
	
	

