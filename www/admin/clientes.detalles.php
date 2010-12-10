<h2>Detalles del cliente</h2><?php

/*
 * Lista de Clientes deudores
 */ 

require_once("controller/clientes.controller.php");


//obtener los clientes deudores del controller de clientes
$cliente = ClienteDAO::getByPK( $_REQUEST['id'] );

?>
<style type="text/css" media="screen">
	#map_canvas { 
		height: 200px;
		width: 400px;
 	}
</style>

  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
  <script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script> 

<script type="text/javascript"> 


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



		
$(document).ready(function() {
	//drawMap();
	GeocoderRequest = {
		address : "<?php echo $cliente->getDireccion(); ?>,<?php echo $cliente->getCiudad(); ?>, Mexico"
	};
	try{

		gc = new google.maps.Geocoder( );

		gc.geocode(GeocoderRequest,  drawMap);
		
	}catch(e){
		console.log(e)
	}
});

</script>




<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>Nombre</b></td><td><?php echo $cliente->getNombre(); ?></td><td rowspan=9><div id="map_canvas"></div></td></tr>
	<tr><td><b>RFC</b></td><td><?php echo $cliente->getRFC(); ?></td></tr>
	<tr><td><b>Direccion</b></td><td><?php echo $cliente->getDireccion(); ?></td></tr>
	<tr><td><b>Ciudad</b></td><td><?php echo $cliente->getCiudad(); ?></td></tr>
	<tr><td><b>Telefono</b></td><td><?php echo $cliente->getTelefono(); ?></td></tr>	
	<tr><td><b>E Mail</b></td><td><?php echo $cliente->getEMail(); ?></td></tr>	
	<tr><td><b>Limite de Credito</b></td><td><?php echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	
	<tr><td><b>Descuento</b></td><td><?php echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
	<tr><td colspan=2><input type=button value="Editar detalles"><input type=button value="Imprmir detalles"></td> </tr>
</table>





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
$tabla->render();	

?>












<h2>Ventas a credito</h2><?php

$ventas = listarVentaCliente($_REQUEST['id'], 'credito');

$header = array( 
	"id_venta" => "Venta", 
	"fecha" => "Fecha", 
	"sucursal" => "Sucursal",
	"cajero" => "Cajero",
	"subtotal" => "Subtotal",
	"descuento" => "Descuento",
	"total" => "Total",
	"pagado" => "Pagado" );
	
$tabla = new Tabla( $header, $ventas );
$tabla->addColRender( "subtotal", "moneyFormat" ); 
$tabla->addColRender( "total", "moneyFormat" ); 
$tabla->addColRender( "pagado", "moneyFormat" ); 
$tabla->addColRender( "descuento", "percentFormat" );

$tabla->addNoData("Este cliente no tiene ventas a credito.");
$tabla->render();


?>











<h2>Abonos</h2><?php

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

	
	
	
	
	
	
	
	
	
	
	
?>