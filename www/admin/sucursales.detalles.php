<?php

/*
 * Lista de Clientes deudores
 */ 

require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");


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

<h2>Mapa de ventas</h2>
<div id="finance">
    <div id="fechas">
    </div>
</div>


<script type="text/javascript" charset="utf-8">
    function mostrarDetallesVenta (vid){
        window.location = "ventas.php?action=detalles&id=" + vid;
    }

<?php


			//obtener la fecha de la primera venta de esta sucursal
            $primeraVenta = SucursalDAO::getByPK( $_REQUEST['id'] )->getFechaApertura();
            $date = new DateTime($primeraVenta);

            $now = new DateTime("now");
       
            $offset = $date->diff($now);


            $ventasEstaSucursal = array();
            $todasLasVentas = array();
            $fechas = array();
                    
            while($offset->format("%r%a") > -1){


        /*        if($offset->format("%r%a") > -1){
                    echo "OK !\n";
                }
                echo $date->format('Y-m-d') . ":\n";
                echo $offset->format("%r%a") . "\n\n";*/


                //buscar las ventas de todas las sucursales
			    $date->setTime ( 0 , 0, 1 );

			    $v1 = new Ventas();
			    $v1->setFecha( $date->format('Y-m-d H:i:s') );


			    $date->setTime ( 23, 59, 59 );
			    $v2 = new Ventas();
			    $v2->setFecha( $date->format('Y-m-d H:i:s') );

			    $results = VentasDAO::byRange($v1, $v2);
                array_push( $todasLasVentas, count($results) );


                //ventas de esta sucursal
			    $v1->setIdSucursal( $_REQUEST['id'] );
			    $results = VentasDAO::byRange($v1, $v2);
                array_push( $ventasEstaSucursal, count($results) );

                array_push( $fechas, $date->format('Y-m-d') );

                //siguiente dia
                $date->add( new DateInterval("P1D") );
                $offset = $date->diff($now);
            }


            echo "\nvar estaSucursal = [";
            for($i = 0; $i < sizeof($ventasEstaSucursal); $i++ ){
                echo  "[" . $i . "," . $ventasEstaSucursal[$i] . "]";
                if($i < sizeof($ventasEstaSucursal) - 1){
                    echo ",";
                }
            }
            echo "];\n";


            echo "var todasSucursales = [";
            for($i = 0; $i < sizeof($todasLasVentas); $i++ ){
                echo  "[" . $i . "," . $todasLasVentas[$i] . "]";
                if($i < sizeof($todasLasVentas) - 1){
                    echo ",";
                }
            }
            echo "];\n";			
			


            echo "var fechasVentas = [";
            for($i = 0; $i < sizeof($fechas); $i++ ){
                echo  "{ fecha : '" . $fechas[$i] . "'}";
                if($i < sizeof($fechas) - 1){
                    echo ",";
                }
            }
            echo "];\n";			
		?>




	Event.observe(document, 'dom:loaded', function() {


	    HumbleFinance.trackFormatter = function (obj) {
            return fechasVentas[ parseInt(obj.x) ].fecha + "\nVentas:" + obj.y ;

	    };

	    HumbleFinance.yTickFormatter = function (n) {
	        if (n == this.axes.y.max) {
	            return false;
	        }

            if(n == 0 ){
                return false;
            }

	        return n + " ventas";
	    };

	    HumbleFinance.xTickFormatter = function (n) { 

	        if (n == 0) {
	            return false;
	        }
			

	        var date = fechasVentas[ parseInt(n) ].fecha;
            return date;
	        date = date.split('-');
            date = date[2];

	        return date; 
	    }

	    HumbleFinance.init('finance', estaSucursal, todasSucursales, todasSucursales);
		

		
	    var xaxis = HumbleFinance.graphs.summary.axes.x;
	    var prevSelection = HumbleFinance.graphs.summary.prevSelection;
	    var xmin = xaxis.p2d(prevSelection.first.x);
	    var xmax = xaxis.p2d(prevSelection.second.x);

	    Event.observe(HumbleFinance.containers.summary, 'flotr:select', function (e) {

			var area = e.memo[0];
	        xmin = Math.floor(area.x1);
	        xmax = Math.ceil(area.x2);

	        var date1 = fechasVentas[xmin].fecha;
	        var date2 = fechasVentas[xmax].fecha;


	        $('fechas').update("Mostrando rango <b>" + date1 + '</b> al <b>' + date2 + "</b>");

	    });

	});

</script>

<h2>Ventas en las ultimas 24 horas</h2><?php

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
$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
$tabla->render();



?>






<h2>Personal</h2><?php



$empleados = listarEmpleados($_REQUEST['id']);
 
$header = array( 
    "id_usuario" => "ID",
    "nombre" => "Nombre",
    "direccion" => "Direccion",
    "telefono" => "Telefono",
    "RFC" => "RFC",
    "puesto" => "Puesto",
    "salario" => "Salario",
    "fecha_inicio"=> "Fecha de Inicio");


$tabla = new Tabla( $header, $empleados );
$tabla->addColRender("salario", "moneyFormat");
$tabla->render();


?>







<h2>Gastos E Ingresos</h2><?php





?>





