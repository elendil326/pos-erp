
<?php

require_once("controller/sucursales.controller.php");
require_once("controller/inventario.controller.php");
require_once("model/ventas.dao.php");


$sucursales = listarSucursales();

$reporteDeRendimientoDiario = new Reporte();
$numeroDeVentasDiarias = new Reporte();


$reporteDeRendimientoDiario->setEscalaEnY	( "pesos" );
$numeroDeVentasDiarias->setEscalaEnY		( "ventas" );

foreach($sucursales as $s)
{
	$reporteDeRendimientoDiario->agregarMuestra	( $s["descripcion"], VentasDAO::rendimientoDiarioEnVentasAContadoPorSucursal( $s["id_sucursal"] ) );
	$numeroDeVentasDiarias->agregarMuestra		( $s["descripcion"], VentasDAO::contarVentasPorDia($s['id_sucursal'], -1 ));
}

$numeroDeVentasDiarias->fechaDeInicio( strtotime(VentasDAO::getByPK( 1 )->getFecha() ) );
$reporteDeRendimientoDiario->fechaDeInicio( strtotime(VentasDAO::getByPK( 1 )->getFecha() ) );


$numeroDeVentasDiarias->graficar		( "Ventas diarias por sucursal" );
echo "<br>";
$reporteDeRendimientoDiario->graficar	( "Ingresos diarios en ventas a contado por sucursal" );


function bold($s){
	return "<b>" . $s . "</b>";
}





//render the table
$header = array( 
	"descripcion"=> "",
	"calle"=> "" );


	$tabla = new Tabla( $header, $sucursales );
	$tabla->addOnClick("id_sucursal", "mostrarDetallesSucursal");
	$tabla->addNoData("No hay sucursales.");
	$tabla->addColRender("descripcion", "bold");

	print ("<br><h2>Sucursales Activas</h2>");
	$tabla->render();
?>

<script type="text/javascript" charset="utf-8">
	function mostrarDetallesSucursal ( sid ){
		window.location = "sucursales.php?action=detalles&id=" + sid;
	}
</script>



<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">

