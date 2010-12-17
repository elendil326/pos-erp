<?php


require_once("model/sucursal.dao.php");
require_once("model/usuario.dao.php");
require_once("model/factura_venta.dao.php");
require_once("controller/clientes.controller.php");


//obtener el cliente
$cliente = ClienteDAO::getByPK( $_REQUEST['id'] );



//titulo
?><h1><?php echo $cliente->getNombre(); ?></h1>



<style type="text/css" media="screen">
	#map_canvas { 
		height: 200px;
		width: 400px;
 	}
</style>

<!--
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
-->

<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>
<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">
<script type="text/javascript"> 

    

    function mostrarDetallesVenta (vid){
        window.location = "ventas.php?action=detalles&id=" + vid;
    }

    function editarCliente (){
        window.location = "clientes.php?action=editar&id=<?php echo $_REQUEST['id']; ?>";
    }


  var drawMap = function ( result, status ) {
	console.log( status, result)

    var myLatlng = result[0].geometry.location;

    var myOptions = {
      zoom: 18,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.HYBRID,
	navigationControl : true
    }
	try{
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	}catch(e){
		
	}
    
  };

    function startMap(){

	    GeocoderRequest = {
		    address : "<?php echo $cliente->getDireccion(); ?>,<?php echo $cliente->getCiudad(); ?>, Mexico"
	    };
	    try{

		    gc = new google.maps.Geocoder( );

		    gc.geocode(GeocoderRequest,  drawMap);
		
	    }catch(e){
		    console.log(e)
	    }


    }

		/*
$(document).ready(function() {

});*/

</script>



<h2>Detalles del cliente</h2>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Nombre</b></td><td><?php echo $cliente->getNombre(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
	<tr><td><b>RFC</b></td><td><?php echo $cliente->getRFC(); ?></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $cliente->getDireccion(); ?></td></tr>
	<tr><td><b>Ciudad</b></td><td><?php echo $cliente->getCiudad(); ?></td></tr>
	<tr><td><b>Telefono</b></td><td><?php echo $cliente->getTelefono(); ?></td></tr>	
	<tr><td><b>E Mail</b></td><td><?php echo $cliente->getEMail(); ?></td></tr>	
	<tr><td><b>Limite de Credito</b></td><td><?php echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	

	<tr><td><b>Descuento</b></td><td><?php echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
	<tr><td><b>Fecha Ingreso</b></td><td><?php echo $cliente->getFechaIngreso() ; ?></td></tr>

	<tr><td><b>Cajero que dio de alta</b></td><td><?php echo UsuarioDAO::getByPK( $cliente->getIdUsuario() )->getNombre() ; ?></td></tr>
	<tr><td><b>Sucursal donde se dio de alta</b></td><td><?php echo SucursalDAO::getByPK( $cliente->getIdSucursal() )->getDescripcion(); ?></td></tr>

	<tr><td colspan=2><input type=button value="Editar detalles" onclick="editarCliente()"></td> </tr>
</table>










<h2>Actividad del cliente</h2>
<div id="finance">
    <div id="fechas">
    </div>
</div>




<script type="text/javascript" charset="utf-8">


            <?php

            //obtener la fecha de la sucursal que abrio primero

            $primerVenta = $cliente->getFechaIngreso();
            $date = new DateTime($primerVenta);

            $now = new DateTime("now");
            $offset = $date->diff($now);


            $numVentas = array();
            $fechas = array();

            $n = 0;

            while($offset->format("%r%a") > -1){


                //if($offset->format("%r%a") > -1){
                //    echo "OK !\n";
                //}
                //echo $date->format('Y-m-d') . ":\n";
                //echo $offset->format("%r%a") . "\n\n";


                //buscar las ventas de todas las sucursales
			    $date->setTime ( 0 , 0, 0 );

			    $v1 = new Ventas();
			    $v1->setFecha( $date->format('Y-m-d H:i:s') );


			    $date->setTime ( 23, 59, 59 );
			    $v2 = new Ventas();
			    $v2->setFecha( $date->format('Y-m-d H:i:s') );

			    $results = VentasDAO::byRange($v1, $v2);

                array_push( $numVentas, count($results) );


                array_push( $fechas, $date->format('Y-m-d') );

                //siguiente dia
                $date->add( new DateInterval("P1D") );
                $offset = $date->diff($now);
            }


            echo "\nvar numVentas = [";
            for($i = 0; $i < sizeof($numVentas); $i++ ){
                echo  "[" . $i . "," . $numVentas[$i] . "]";
                if($i < sizeof($numVentas) - 1){
                    echo ",";
                }
            }
            echo "];\n";




            echo "var fechas = [";
            for($i = 0; $i < sizeof($fechas); $i++ ){
                echo  "{ fecha : '" . $fechas[$i] . "'}";
                if($i < sizeof($fechas) - 1){
                    echo ",";
                }
            }
            echo "];\n";	

		?>




	Event.observe(document, 'dom:loaded', function() {

        //iniciar mapa
        startMap();


	    HumbleFinance.trackFormatter = function (obj) {
            return fechas[ parseInt(obj.x) ].fecha + "\nVentas:" + parseInt(obj.y) ;

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
			

            try{
    	        var date = fechas[ parseInt(n) ].fecha;
            }catch(e){
                return "";
            }

	        return date; 
	    }

	    HumbleFinance.init('finance', numVentas, [], numVentas);
		

		
	    var xaxis = HumbleFinance.graphs.summary.axes.x;
	    var prevSelection = HumbleFinance.graphs.summary.prevSelection;
	    var xmin = xaxis.p2d(prevSelection.first.x);
	    var xmax = xaxis.p2d(prevSelection.second.x);

	    Event.observe(HumbleFinance.containers.summary, 'flotr:select', function (e) {

			var area = e.memo[0];
	        xmin = Math.floor(area.x1);
	        xmax = Math.ceil(area.x2);

	        var date1 = fechas[xmin].fecha;
	        var date2 = fechas[xmax].fecha;


	        $('fechas').update("Mostrando rango <b>" + date1 + '</b> al <b>' + date2 + "</b>");

	    });

	});

</script>






















<h2>Ventas a contado</h2><?php

$ventas = listarVentaCliente($_REQUEST['id'], 'contado');



//render the table
$header = array( 
	"id_venta" => "Venta", 
	"fecha" => "Fecha", 
	"sucursal" => "Sucursal",
	"cajero" => "Cajero",
	"subtotal" => "Subtotal",
	"descuento" => "Descuento",
	"total" => "Total");


$tabla = new Tabla( $header, $ventas );
$tabla->addColRender( "subtotal", "moneyFormat" ); 
$tabla->addColRender( "total", "moneyFormat" ); 
$tabla->addColRender( "descuento", "percentFormat" ); 
$tabla->addNoData("Este cliente no tiene ventas a contado.");
$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
$tabla->render();	

?>








<h2>Ventas facturadas</h2><?php

$ventas = listarVentaCliente($_REQUEST['id']);

$ventasFacturadas = array();

foreach( $ventas as $venta ){

       $fv = new FacturaVenta();
       $fv->setIdVenta( $venta['id_venta'] );

       if(sizeof(FacturaVentaDAO::search($fv))> 0){
            array_push($ventasFacturadas, $venta);
       }
    
}


function buscarFolio($id){
       $fv = new FacturaVenta();
       $fv->setIdVenta( $id );
       $r = FacturaVentaDAO::search($fv);
       if(sizeof($r) > 0)
        return $r[0]->getFolio();
       else
        return null;
}

$header = array( 
	"id_venta" => "Folio",
	"fecha" => "Fecha", 
	"sucursal" => "Sucursal",
	"cajero" => "Cajero",
	"subtotal" => "Subtotal",
	"descuento" => "Descuento",
	"total" => "Total",
	/*"pagado" => "Pagado"*/);
	
$tabla = new Tabla( $header, $ventasFacturadas );
$tabla->addColRender( "subtotal", "moneyFormat" ); 
$tabla->addColRender( "saldo", "moneyFormat" ); 
$tabla->addColRender( "total", "moneyFormat" ); 
$tabla->addColRender( "pagado", "moneyFormat" ); 
$tabla->addColRender( "id_venta", "buscarFolio" ); 
$tabla->addColRender( "descuento", "percentFormat" );

$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
$tabla->addNoData("Este cliente no tiene ventas que se hayan facturado.");
$tabla->render();


?>






<h2>Ventas a credito</h2><?php


function checkSaldoNum( $n ){
    if($n != 0){
       return "<font color=red>".moneyFormat($n). "</font>";
    }
    return moneyFormat($n);
}

$ventasCredito = listarVentaCliente($_REQUEST['id'], 'credito');


$header = array( 
	"id_venta" => "Venta", 
	"fecha" => "Fecha", 
	"sucursal" => "Sucursal",
	"cajero" => "Cajero",
	"subtotal" => "Subtotal",
	"descuento" => "Descuento",
	"total" => "Total",
	"pagado" => "Pagado",
	"saldo" => "Saldo");
	
$tabla = new Tabla( $header, $ventasCredito );
$tabla->addColRender( "subtotal", "moneyFormat" ); 
$tabla->addColRender( "saldo", "checkSaldoNum" ); 
$tabla->addColRender( "total", "moneyFormat" ); 
$tabla->addColRender( "pagado", "moneyFormat" ); 
$tabla->addColRender( "descuento", "percentFormat" );

$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
$tabla->addNoData("Este cliente no tiene ventas a credito.");



$totalDeuda = 0;
foreach ($ventasCredito as $venta)
{
     
    $totalDeuda += $venta['saldo'];
}

if(sizeof($ventasCredito) > 0){
    echo "<h3>Saldo pendiente : " . moneyFormat($totalDeuda) . "</h3>";
}


$tabla->render();
?>









<?php
if(sizeof($ventasCredito) > 0){

    ?><h2>Abonos</h2><?php

    $abonos = listarAbonos( $_REQUEST['id'] );

    $header = array( 
	    "id_pago" => "Pago", 
	    "id_venta" => "Venta", 
	    "sucursal" => "Sucursal",
	    "cajero" => "Cajero",
	    "fecha" => "Fecha",
	    "monto" => "Monto" );

    $tabla = new Tabla( $header, $abonos );
    $tabla->addColRender( "monto", "moneyFormat" ); 
    $tabla->addNoData("Este cliente no ha realizado ningun abono.");
    $tabla->render();
 } 

?>
