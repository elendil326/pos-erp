<?php

require_once("controller/sucursales.controller.php");
require_once("controller/inventario.controller.php");
require_once("model/ventas.dao.php");


$sucursales = listarSucursales();



?><h2>Sucursales Activas</h2><br><?php

echo "<div align=center>";
foreach($sucursales as $s){
	echo  "<span class='prod rounded'  ><a style='text-decoration:none' href='sucursales.php?action=detalles&id=". $s["id_sucursal"] ."' >" . $s["descripcion"]  . "</a></span>&nbsp;";
}
echo "</div>"

?>
<style> 
.prod {
    background:#fff;
    color:#333;
    text-decoration:none;
    padding:5px 10px;
	border:1px solid #fff;
 
 
    /* Add the transition properties! */
   	-webkit-transition-property: background-color, color, border; 
    -webkit-transition-duration: 300ms;
 
    /* you can control the acceleration curve here */
    -webkit-transition-timing-function: ease-in-out; 
}
 
.prod:hover {
    background:#D7EAFF;
    color:#000;
    border:1px solid #3F8CE9;
}
</style> 

<?php

$reporteDeRendimientoDiario = new Reporte();
$numeroDeVentasDiarias = new Reporte();


$reporteDeRendimientoDiario->setEscalaEnY	( "pesos" );
$numeroDeVentasDiarias->setEscalaEnY		( "ventas" );

foreach($sucursales as $s)
{
	$reporteDeRendimientoDiario->agregarMuestra	( $s["descripcion"], VentasDAO::rendimientoDiarioEnVentasAContadoPorSucursal( $s["id_sucursal"] ) );
	$numeroDeVentasDiarias->agregarMuestra		( $s["descripcion"], VentasDAO::contarVentasPorDia($s['id_sucursal'], -1 ));
}

if( VentasDAO::getByPK( 1 ) != null ){
	$numeroDeVentasDiarias->fechaDeInicio( strtotime(VentasDAO::getByPK( 1 )->getFecha() ) );
	$reporteDeRendimientoDiario->fechaDeInicio( strtotime(VentasDAO::getByPK( 1 )->getFecha() ) );


	$numeroDeVentasDiarias->graficar		( "Ventas diarias por sucursal" );
	echo "<br>";
	$reporteDeRendimientoDiario->graficar	( "Ingresos diarios en ventas a contado por sucursal" );	
}


?>




<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">

