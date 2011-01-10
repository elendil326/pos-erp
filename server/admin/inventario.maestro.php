<?php


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");

require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');

?>


<h1>Inventario Maestro</h1>
<script>
	function d( json ){
		o = $.JSON.decodeSecure(Url.decode(json));
		window.location = "inventario.php?action=detalleCompra&compra=" + o. id_compra_proveedor + "&producto=" + o.id_producto;
	}
</script>
<?php



$iMaestro = listarInventarioMaestro() ;

$header = array(
	"folio" 			=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas",
	"peso_por_arpilla"	=> "Kg/Arpilla",
	"productor"			=> "Productor",
	"fecha"				=> "Llegada",
	//"transporte"				=> "Transporte",
	"merma_por_arpilla"			=> "Merma",
	"sitio_descarga_desc"		=> "Sitio de descarga",
	"existencias"				=> "Existencias",
	"existencias_procesadas"	=> "Limpias" );
	
$tabla = new Tabla( $header, $iMaestro );
$tabla->addOnClick("folio", "d", true);
$tabla->render();

?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.min.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">
