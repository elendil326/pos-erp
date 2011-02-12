
<?php

require_once("controller/sucursales.controller.php");
require_once("controller/inventario.controller.php");



$sucursales = listarSucursales();





$data = array();

foreach( $sucursales as $sucursal ){

	//obtener los clientes del controller de clientes
	$detalles = detallesSucursal( $sucursal["id_sucursal"] );
	array_push($data, $detalles); 
}






/****************************
 * Grafica 
 *****************************/
?>
<h2>Ventas por sucursales</h2><br>
<div id="graph">
    <div id="fechas">
    </div>
</div>

<script type="text/javascript" charset="utf-8">

<?php

    $ventas = array();//ventas
		
		
    $fechas = array();
    
	//obtener la fecha de la primera venta de esta sucursal
    $primeraVenta = VentasDAO::getByPK( 1 )->getFecha();

	$start = date("Y-m-d", strtotime("-1 day", strtotime($primeraVenta)));
	$now = date ( "Y-m-d" );
    
	$v1 = new Ventas();
	$v2 = new Ventas();
	
	$todas = "var todas = [";
	$foo =  0;
	
    while(true){
    	
	    $v1->setFecha( $start . " 00:00:00" );
	    $v2->setFecha( $start . " 23:59:59" );
		
        array_push( $fechas, $start );

		$sum = 0;
		
		foreach( $sucursales as $sucursal ){

	    	$v1->setIdSucursal( $sucursal['id_sucursal']  );
	
		    $results = VentasDAO::byRange($v1, $v2);
		
			if( !isset($ventas[$sucursal['id_sucursal'] ] ))
				$ventas[ $sucursal['id_sucursal'] ] = array();
				
	        array_push( $ventas[ $sucursal['id_sucursal'] ], count($results) );			
			$sum += count($results);
		}

		$todas .= "[ $foo, $sum ], ";
		$foo++;
		
		if($start == $now){
			break;
		}
		
		$start = date("Y-m-d", strtotime("+1 day", strtotime($start)));        

    }

	$todas .= "]";
    
	echo "var ventas = [];";
 
	foreach($ventas as $suc => $key){

        echo  "ventas[$suc] = [];";
		for($i = 0; $i < sizeof($key); $i++ ){
	        echo  "ventas[$suc].push([" . $i . "," . $key[$i] . "]);\n";
	   
	    }
	}
	
	echo $todas;
?>


var graficaVentas;

Event.observe(document, 'dom:loaded', function() {

	graficaVentas = new HumbleFinance();

	<?php
		foreach($ventas as $suc => $key){
 			echo "graficaVentas.addGraph( ventas[$suc] ); 	";
		}
	?>


    graficaVentas.addSummaryGraph( todas );
    graficaVentas.render('graph');
    

});
</script>


<?php



function bold($s){
	return "<b>" . $s . "</b>";
}
//render the table
$header = array( 
	//"id_sucursal" => "ID",
	"descripcion"=> "",
	"direccion"=> "" );
	//"rfc"=> "RFC",
	//"telefono"=> "Telefono",
	//"letras_factura"=> "Facturas" );
$tabla = new Tabla( $header, $data );
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



<?php

	include( "admin/sucursales.abrir.php" );

?>