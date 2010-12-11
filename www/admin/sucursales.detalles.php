<?php

/*
 * Lista de Clientes deudores
 */ 

require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");

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

<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>
<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">

<h2>Ventas</h2>
<div id="finance">
    <div id="labels">
    </div>
</div>


<script type="text/javascript" charset="utf-8">


	Event.observe(document, 'dom:loaded', function() {


	    HumbleFinance.trackFormatter = function (obj) {
			return "Hola" + obj.x+"," + obj.y;

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
			
			return n;
	        var date = jsonData[n].date;
	        date = date.split(' ');
	        date = date[2];

	        return date; 
	    }
		
		<?php
			echo "/*";

			//obtener la fecha de la primera venta de esta sucursal
			$primeraVenta = new Ventas();
			$primeraVenta->setIdSucursal( $_REQUEST['id'] );

			


			$day = 27;
			$month = 11;
			$year = 2010;

			$d = new DateTime();
			$d->setTimezone( new DateTimeZone("America/Mexico_City"));			
			$d->setDate ( $year , $month , $day );
			$d->setTime ( 0 , 0, 1 );

			$v1 = new Ventas();
			$v1->setFecha( $d->format('Y-m-d H:i:s') );
			$v1->setIdSucursal( $_REQUEST['id'] );

			$d->setTime ( 23, 59, 59 );
			$v2 = new Ventas();
			$v2->setFecha( $d->format('Y-m-d H:i:s') );

			$results = VentasDAO::byRange($v1, $v2);
			echo count($results);
			
			
			
			//strtotime("10 September 2000")

			//var_dump( $ventasSuc );



			echo "*/";			
		?>

		data1 = [[0,100.34],[1,108.31],[2,109.40],[3,104.87],[4,106.00],[5,107.91],[6,100.34],[7,108.31],[8,109.40],[9,104.87],[10,106.00],[11,107.91]];
		data2 =   [[0,50],[1,100],[2,25],[3,75],[4,45],[5,35],[6,33.34],[7,44.31],[8,22.40],[9,122.87],[10,33.00],[11,107.91]];
		sum =   [[0,100.34],[1,108.31],[2,109.40],[3,104.87],[4,106.00],[5,107.91],[6,100.34],[7,108.31],[8,109.40],[9,104.87],[10,106.00],[11,107.91]];
		

	    HumbleFinance.init('finance', data1, data2, sum);
		

		
	    var xaxis = HumbleFinance.graphs.summary.axes.x;
	    var prevSelection = HumbleFinance.graphs.summary.prevSelection;
	    var xmin = xaxis.p2d(prevSelection.first.x);
	    var xmax = xaxis.p2d(prevSelection.second.x);

	    Event.observe(HumbleFinance.containers.summary, 'flotr:select', function (e) {
/*
			var area = e.memo[0];
	        xmin = Math.floor(area.x1);
	        xmax = Math.ceil(area.x2);

	        var date1 = jsonData[xmin].date;
	        var date2 = jsonData[xmax].date;

	        $('dateRange').update(jsonData[xmin].date + ' - ' + jsonData[xmax].date);
*/
	    });

	});

</script>

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










