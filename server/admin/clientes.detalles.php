<?php


require_once("model/sucursal.dao.php");
require_once("model/usuario.dao.php");
require_once("model/factura_venta.dao.php");
require_once("controller/clientes.controller.php");


//obtener el cliente
$cliente = ClienteDAO::getByPK( $_REQUEST['id'] );



?>

<script>document.getElementById("MAIN_TITLE").innerHTML = "<?php echo $cliente->getNombre(); ?>";</script>


<style type="text/css" media="screen">
	#map_canvas { 
		height: 200px;
		width: 400px;
 	}
</style>



<h2>Detalles del cliente</h2>
<table border="0" cellspacing="1" cellpadding="1" >
	
	<tr><td style="width:200px"><b>Nombre</b></td><td style="width:200px">			<?php 	echo $cliente->getNombre(); ?></td>
			<td valign="top" rowspan=12><div id="map_canvas"></div></td></tr>
	<tr><td><b>RFC</b></td><td>				<?php 	echo $cliente->getRFC(); ?></td></tr>
	<tr><td><b>Direccion</b></td><td>		<?php 	echo $cliente->getDireccion(); ?></td></tr>
	<tr><td><b>Ciudad</b></td><td>			<?php 	echo $cliente->getCiudad(); ?></td></tr>
	<tr><td><b>Telefono</b></td><td>		<?php 	echo $cliente->getTelefono(); ?></td></tr>	
	<tr><td><b>E Mail</b></td><td>			<?php 	echo $cliente->getEMail(); ?></td></tr>	
	<tr><td><b>Limite de Credito</b></td><td><?php 	echo moneyFormat($cliente->getLimiteCredito()); ?></td></tr>	
	<tr><td><b>Descuento</b></td><td>		<?php 	echo percentFormat( $cliente->getDescuento() ); ?></td></tr>
	<tr><td><b>Fecha Ingreso</b></td><td>	<?php 	echo toDate( $cliente->getFechaIngreso() ); ?></td></tr>
	<tr><td><b>Gerente que dio de alta</b></td><td><?php echo UsuarioDAO::getByPK( $cliente->getIdUsuario() )->getNombre() ; ?></td></tr>
	
	<?php
		$foo = SucursalDAO::getByPK( $cliente->getIdSucursal() );
		$_suc = $foo == null ? "Ninguna" : $foo->getDescripcion();
	?>
	<tr><td><b>Sucursal donde se dio de alta</b></td><td><?php echo $_suc; ?></td></tr>

	<tr><td colspan=3>
		<h4>
		<input type=button value="Editar detalles de cliente" onclick="editarCliente()">
		<input type=button value="Vender a este cliente" onclick="window.location='ventas.php?action=vender&cid=<?php echo $_REQUEST['id']; ?>'">
		</h4>
	</td> </tr>
</table>










<h2>Actividad del cliente</h2>
<div id="finance">
    <div id="fechas">
    </div>
</div>




<script type="text/javascript" charset="utf-8">


            <?php

            //graficar desde la fecha de ingreso de este cliente
            $primerVenta = $cliente->getFechaIngreso();
       		$start = date("Y-m-d", strtotime("-1 day", strtotime($primerVenta)));
			$now = date ( "Y-m-d" );
			$activo = false;
            $numVentas = array();
            $fechas = array();
            $n = 0;

            while(true){
				//buscar las ventas de todas las sucursales
				$v1 = new Ventas();
				$v1->setFecha( $start . " 00:00:00" );
				$v1->setIdCliente( $_REQUEST['id'] );

				$v2 = new Ventas();
				$v2->setFecha( $start . " 23:59:59" );

				$results = VentasDAO::byRange($v1, $v2);
				
				if(count($results) > 0){
					$activo = true;
				}
				array_push( $numVentas, count($results) );
		        array_push( $fechas, $start );

		        $foo = explode("-", $start);
		        $bar = explode("-", $now );
		        
		        if(($foo[0] > $bar[0]) && ($foo[1] > $bar[1]) && ($foo[2] > $bar[2])){
		        	break;
		        }
		        
				if($start == $now){
					break;
				}
				
				$start = date("Y-m-d", strtotime("+1 day", strtotime($start))); 

         	}

			echo "var actividad = " . ( $activo === true ? "true" : "false" ) . ";";

			if($activo){
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
			}

            

		?>




	Event.observe(document, 'dom:loaded', function() {

        //iniciar mapa

        <?php 
            if(POS_ENABLE_GMAPS){ ?>startMap();<?php }
        ?>

		if( !actividad ){
			$("finance").update("<h3>Este cliente no ha realizado ninguna compra.</h3>");
		}else{
			var graficaVentas = new HumbleFinance();
		    graficaVentas.addGraph( numVentas );
		    graficaVentas.addSummaryGraph( numVentas );
		    graficaVentas.render('finance');
		}


	});

</script>






















<?php
if($activo){
	$ventas = listarVentaCliente($_REQUEST['id'], 'contado');
	
	//render the table
	$header = array( 
		"id_venta" => "Venta", 
		"fecha" => "Fecha", 
		"sucursal" => "Sucursal",
		"cajero" => "Cajero",
		"subtotal" => "Subtotal",
		//"descuento" => "Descuento",
		"total" => "Total");


	$tabla = new Tabla( $header, $ventas );
	$tabla->addColRender( "subtotal", "moneyFormat" ); 
	$tabla->addColRender( "total", "moneyFormat" ); 
	//$tabla->addColRender( "descuento", "percentFormat" );
	$tabla->addColRender( "fecha", "toDate" );	
	$tabla->addNoData("Este cliente no tiene ventas a contado.");
	$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
	
	echo "<br><h2>Ventas a contado</h2>";
	$tabla->render();	
}

if($activo){


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
	$tabla->addColRender( "fecha", "toDate" );		
	$tabla->addColRender( "descuento", "percentFormat" );

	$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
	$tabla->addNoData("Este cliente no tiene ventas que se hayan facturado.");
	echo "<h2>Ventas facturadas</h2>";
	$tabla->render();

}


function checkSaldoNum( $n ){
    if($n != 0){
       return "<font color=red>".moneyFormat($n). "</font>";
    }
    return moneyFormat($n);
}


if($activo){



	$ventasCredito = listarVentaCliente($_REQUEST['id'], 'credito');


	$header = array( 
		"id_venta" => "Venta", 
		"fecha" => "Fecha", 
		"sucursal" => "Sucursal",
		"cajero" => "Cajero",
//		"subtotal" => "Subtotal",
//		"descuento" => "Descuento",
		"total" => "Total",
		"pagado" => "Pagado",
		"saldo" => "Saldo");
	
	$tabla = new Tabla( $header, $ventasCredito );
//	$tabla->addColRender( "subtotal", "moneyFormat" ); 
	$tabla->addColRender( "saldo", "checkSaldoNum" ); 
	$tabla->addColRender( "total", "moneyFormat" ); 
	$tabla->addColRender( "pagado", "moneyFormat" ); 
//	$tabla->addColRender( "descuento", "percentFormat" );
	$tabla->addColRender( "fecha", "toDate" );	
	$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
	$tabla->addNoData("Este cliente no tiene ventas a credito.");



	$totalDeuda = 0;
	foreach ($ventasCredito as $venta)
	{
		 
		$totalDeuda += $venta['saldo'];
	}



	echo "<h2>Ventas a credito</h2>";
	$tabla->render();
	if(sizeof($ventasCredito) > 0){
		echo "<h4>Saldo pendiente : " . moneyFormat($totalDeuda) . "</h4>";
	}
}





if($activo && (sizeof($ventasCredito) > 0)){

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
	$tabla->addColRender( "fecha", "toDate" );	
    $tabla->render();
 } 

?>



<?php 
if(POS_ENABLE_GMAPS){
    ?><script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script><?php
}
?>


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


    if(result.length == 0){
        document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible localizar esta direccion. </div>"; 
        return;
    }

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
        document.getElementById("map_canvas").innerHTML = "<div align='center'> Imposible crear el mapa.</div>";
        return;
	}
    

    m = new google.maps.Marker({
        map: map,
        position: myLatlng
    });


  };

    function startMap(){

	    GeocoderRequest = {
		    address : "<?php echo $cliente->getDireccion(); ?>, <?php echo $cliente->getCiudad(); ?>, Mexico"
	    };
	    try{

		    gc = new google.maps.Geocoder( );

		    gc.geocode(GeocoderRequest,  drawMap);
		
	    }catch(e){
		    console.log(e)
	    }


    }



</script>