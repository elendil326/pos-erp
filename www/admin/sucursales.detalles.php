<?php

/*
 * Lista de Clientes deudores
 */ 

require_once("controller/sucursales.controller.php");


//obtener los clientes deudores del controller de clientes
$sucursal = SucursalDAO::getByPK( $_REQUEST['id'] );

print( "<h1>" . $sucursal->getDescripcion() . "</h1>");

?>


<h2>Detalles</h2>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Descripcion</b></td><td><?php echo $sucursal->getDescripcion(); ?></td><td rowspan=9><div id="map_canvas"></div></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $sucursal->getDireccion(); ?></td></tr>
	<tr><td><b>Gerente</b></td><td><?php echo $sucursal->getGerente(); ?></td></tr>
	<tr><td><b>ID</b></td><td><?php echo $sucursal->getIdSucursal(); ?></td></tr>
	<tr><td><b>Letras factura</b></td><td><?php echo $sucursal->getLetrasFactura(); ?></td></tr>	
	<tr><td><b>RFC</b></td><td><?php echo $sucursal->getRfc(); ?></td></tr>	
	<tr><td><b>Telefono</b></td><td><?php echo $sucursal->getTelefono(); ?></td></tr>	

	<tr><td colspan=2><input type=button value="Editar detalles"><input type=button value="Imprmir detalles"></td> </tr>
</table>


<h2>Ventas</h2><?php

$ventas = ventasSucursal(  $sucursal->getIdSucursal() );


$header = array( 
	"id_venta" =>  "Venta",
	"fecha" =>  "Fecha",
	"cliente" =>  "Cliente",
	"cajero" => "Cajero",
	"tipo_venta" =>  "Tipo",
	"subtotal" =>  "Subtotal",	
	"descuento" => "Descuento",
	"total" =>  "Total",
	"pagado" =>  "Pagado");


$tabla = new Tabla( $header, $ventas );
$tabla->render();



?>

<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>
<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>




<h2>Ventas</h2>
<div id="finance">
    <div id="labels">
        <div id="time">
            <a onclick="HumbleFinance.zoom(5);">1w</a>
            <a onclick="HumbleFinance.zoom(21);">1m</a>
            <a onclick="HumbleFinance.zoom(65);">3m</a>
            <a onclick="HumbleFinance.zoom(127);">6m</a>
            <a onclick="HumbleFinance.zoom(254);">1y</a>
            <a onclick="HumbleFinance.zoom(1265);">5y</a>
        </div>
        <div id="dateRange"></div>
    </div>
</div>


<script type="text/javascript" charset="utf-8">


	Event.observe(document, 'dom:loaded', function() {

	    //prettyPrint();

	    HumbleFinance.trackFormatter = function (obj) {

	        var x = Math.floor(obj.x);
	        var data = jsonData[x];
	        var text = data.date + " Price: " + data.close + " Vol: " + data.volume;

	        return text;
	    };

	    HumbleFinance.yTickFormatter = function (n) {

	        if (n == this.axes.y.max) {
	            return false;
	        }

	        return '$'+n;
	    };

	    HumbleFinance.xTickFormatter = function (n) { 

	        if (n == 0) {
	            return false;
	        }

	        var date = jsonData[n].date;
	        date = date.split(' ');
	        date = date[2];

	        return date; 
	    }

	    HumbleFinance.init('finance', priceData, volumeData, summaryData);
	    HumbleFinance.setFlags(flagData); 

	    var xaxis = HumbleFinance.graphs.summary.axes.x;
	    var prevSelection = HumbleFinance.graphs.summary.prevSelection;
	    var xmin = xaxis.p2d(prevSelection.first.x);
	    var xmax = xaxis.p2d(prevSelection.second.x);

	    $('dateRange').update(jsonData[xmin].date + ' - ' + jsonData[xmax].date);

	    Event.observe(HumbleFinance.containers.summary, 'flotr:select', function (e) {

	        var area = e.memo[0];
	        xmin = Math.floor(area.x1);
	        xmax = Math.ceil(area.x2);

	        var date1 = jsonData[xmin].date;
	        var date2 = jsonData[xmax].date;

	        $('dateRange').update(jsonData[xmin].date + ' - ' + jsonData[xmax].date);
	    });
	});
</script>








