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


?>
<!--
	Seleccion de producto a surtir
-->
<div  >
	<h2>Productos</h2>
		<?php
		echo "<table border=0 style='width: 100%; font-size: 14px; cursor: pointer;'>";
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

				echo "<td id='producto-" . $productos[$a]->getIdProducto() . "'  onClick='detalle_inventario( " .  $productos[$a]->getIdProducto() . " )' onmouseover=\"this.style.backgroundColor = '#D7EAFF'\" onmouseout=\"this.style.backgroundColor = 'white'\"><img style='float:left;' src='../media/icons/basket_32.png'>" . $productos[$a]->getDescripcion() . "<br>";
				//echo "<b>" . number_format( $totals , 2) ."</b>&nbsp;" .$productos[$a]->getEscala() . "s<br/><br/>";
				echo " " . moneyFormat($lastOne->getPrecioVenta()) .  "<br><br>";
				echo "</td>";
			}
			echo "</tr>";
		echo "</table>";
		?>
</div>
<?php


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


function toUnit( $e, $row )
{
	if($e == "NA"){
		return  "";
	}
	
	if(isset($row['peso_por_arpilla'])){
		
		if( $row["medida"] == "kilogramo" ){
			return "<b>" . number_format($e/$row['peso_por_arpilla'], 2) . "</b>Arp  " . "(<b>" . number_format($e, 2) . "</b>".smallUnit($row["medida"]).") ";			
		}

		return "<b>" . number_format($e, 2) . "</b>".smallUnit($row["medida"]) ;
	}
	
	return "<b>" . number_format($e, 2) . "</b>kg";
}


function toUnitProc($e, $row){
	if($e == "NA"){
		return  "";
	}
	

	return "<b>" . number_format($e/60, 2) . "</b>Arp  " . "(<b>" . number_format($e, 2) . "</b> ".smallUnit($row["medida"]).") ";

	
}


function smallUnit($unit){
	switch( $unit ){
		case "kilogramo": return "Kgs";
		case "pieza": return "Pzas";
	}
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
	"fecha"				=> "Llegada",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas origen",
	"peso_por_arpilla"	=> "Promedio",
	//"productor"			=> "Productor",
	//"transporte"				=> "Transporte",
	//"merma_por_arpilla"			=> "Merma",
	//"sitio_descarga_desc"		=> "Sitio de descarga",
	"existencias"				=> "Existencias",
	"existencias_procesadas"	=> "Procesadas" );

$tabla = new Tabla( $header, $iMaestro );
$tabla->addOnClick("folio", "d", true);
$tabla->addColRender( "existencias", "toUnit" );
$tabla->addColRender( "existencias_procesadas", "toUnitProc" );
$tabla->addColRender( "fecha", "toDateS" );
$tabla->render();



$iMaestroTerminados = listarInventarioMaestro(100, POS_SOLO_VACIOS) ;
echo "<h2>Embarques agotados</h2>";
$header = array(
	"folio" 			=> "Remision",
	"producto_desc" 	=> "Producto",
	"variedad" 	 		=> "Variedad",
	"arpillas"			=> "Arpillas origen",
	"peso_por_arpilla"	=> "Promedio",
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

