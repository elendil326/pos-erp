<?php


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");

require_once('model/actualizacion_de_precio.dao.php');
require_once('model/compra_proveedor.dao.php');


$iMaestro = listarInventarioMaestro(200, POS_SOLO_ACTIVOS) ;

?>



<script>
	jQuery("#MAIN_TITLE").html("Inventario Maestro");
	help = "<h2>Invenario Maestro</h2>";
	help += "El inventario maestro es donde se concentra la informacion sobre ";
	help += "todos los proudcotos y camiones..";
	jQuery("#ayuda").html(help);
</script>


<script>
	function d( json ){
		o = jQuery.JSON.decodeSecure(Url.decode(json));
		window.location = "inventario.php?action=detalleCompra&compra=" + o. id_compra_proveedor + "&producto=" + o.id_producto;
	}
</script>
<?php

$productos = InventarioDAO::getAll();

echo "<h2>Productos</h2>";
echo "<table border=0 style='width: 100%; font-size: 14px;'>";
	echo "<tr>";
	for($a = 0; $a < sizeof($productos); $a++){
		
		//buscar su precio sugerido actual
		$act = new ActualizacionDePrecio();
		$act->setIdProducto( $productos[$a]->getIdProducto() );
		$res = ActualizacionDePrecioDAO::search($act, "fecha", "desc");
		$lastOne = $res[0];
		
		//buscar todas las existencias
		$totals = 0;
		for($i = 0; $i < sizeof($iMaestro); $i++){
			if($iMaestro[$i]['id_producto'] == $productos[$a]->getIdProducto()){
				$totals +=  $iMaestro[$i]['existencias'];
			}
		
		}
		if($a % 5 == 0){
			echo "</tr><tr>";
		}
		
		echo "<td ><a href='inventario.php?action=detalle&id=". $productos[$a]->getIdProducto() ."'>" . $productos[$a]->getDescripcion() . "</a><br>";
		echo "<b>" . number_format( $totals , 2) ."</b>" .$productos[$a]->getEscala() . "s<br/>";
		echo "" . moneyFormat($lastOne->getPrecioVenta()) ;
		echo "</td>";
	}
	echo "</tr>";
echo "</table>";


?><script>
	function detalle_inventario(id){
		window.location = "inventario.php?action=detalle&id="+ id;
	}
</script>
<style>
	table{
		font-size: 11px;
	}
</style>

<?php


function toUnit( $e )
{
	if($e == "NA"){
		return  "<i>N/A</i>";
	}
	return "<b>" . number_format($e, 2) . "</b>kg";
}


function toDateS( $d ){
	$foo = toDate($d);
	$bar = explode(" ", $foo);
	return $bar[0];
}

function tachar($s){
	return "<strike>".$s."</strike>";
}






// @TODO Existencias arpillas
echo "<h2>Embarques activos de proveedores</h2>";
$header = array(
	"folio" 			=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas origen",
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
$tabla->addColRender( "existencias", "toUnit" );
$tabla->addColRender( "existencias_procesadas", "toUnit" );
$tabla->addColRender( "fecha", "toDateS" );
$tabla->render();



$iMaestroTerminados = listarInventarioMaestro(100, POS_SOLO_VACIOS) ;
echo "<h2>Embarques agotados</h2>";
$header = array(
	"folio" 			=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas origen",
	"peso_por_arpilla"	=> "Kg/Arpilla",
	"productor"			=> "Productor",
	"fecha"				=> "Llegada",
	//"transporte"				=> "Transporte",
	"merma_por_arpilla"			=> "Merma",
	"sitio_descarga_desc"		=> "Sitio de descarga" );

$tabla = new Tabla( $header, $iMaestroTerminados );
$tabla->addOnClick("folio", "d", true);
$tabla->addColRender( "existencias", "toUnit" );
$tabla->addColRender( "existencias_procesadas", "toUnit" );
$tabla->addColRender( "fecha", "toDateS" );
$tabla->addColRender( "folio", "tachar" );
$tabla->render();
?>

