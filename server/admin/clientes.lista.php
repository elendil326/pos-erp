<?php


require_once("controller/clientes.controller.php");
require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");

?>



<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>

<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">


<?php

	
	//obtener todas las sucursales activas
	$sucursales = listarSucursales(  );
	
	$clientesRegistradosPorSucursal = array();
	
	//buscar cuantos clientes se han registado en todas las sucursales
	$foo = 0;
	foreach($sucursales as $s){
		$clientesRegistradosPorSucursal[ $foo ] = ClienteDAO::contarClientesRegistradosPorDia( $s["id_sucursal"] );
		$missingDays[ $foo ] = 0;
		$sucDayIndex[ $foo ] = 0;
		$foo++;
	}
	
	//obtener la sucursal que abrio primero
    $firstSuc = SucursalDAO::getAll(1, 1, 'fecha_apertura', 'ASC' );
	
	//esa el la fecha que comenzare a iterar
	$dayIndex =  date("Y-m-d",  strtotime($firstSuc[0]->getFechaApertura())  );
	
	//the day the loop will end
	$tomorrow = date("Y-m-d", strtotime("+1 day",  time()));	
	
	
	//array donde guardamos las fechas que hemos estado analizando
	$fechas = array();
	

	
	while( $tomorrow != $dayIndex ){
		
		for ($sucursalIndex=0; $sucursalIndex < sizeof($clientesRegistradosPorSucursal); $sucursalIndex++) { 
			
			//im out of days !
			if( sizeof($clientesRegistradosPorSucursal[ $sucursalIndex ]) == $sucDayIndex[ $sucursalIndex ] ){
				array_push($clientesRegistradosPorSucursal[ $sucursalIndex ], array( "fecha" => $dayIndex, "clientes" => 0 ));
			}
			
			if( $clientesRegistradosPorSucursal[ $sucursalIndex ][ $sucDayIndex[ $sucursalIndex ] ]["fecha"] != $dayIndex){
				$missingDays[ $sucursalIndex ]++;
			}else{
				for($a = 0 ; $a < $missingDays[ $sucursalIndex ]; $a++){
					array_splice($clientesRegistradosPorSucursal[ $sucursalIndex ], $sucDayIndex[ $sucursalIndex ], 0, array(array( "fecha" => "missing_day" , "clientes" => 0)) );
				}
			 	$sucDayIndex[ $sucursalIndex ] += $missingDays[ $sucursalIndex ]+1;
				$missingDays[ $sucursalIndex ] = 0;
			}
			
		}
		
		
		array_push($fechas, $dayIndex);
		$dayIndex = date("Y-m-d", strtotime("+1 day", strtotime($dayIndex)));
	}
	
	

	
?>
<h2>Mapa de clientes por sucursal</h2>
<div id="finance">
    <div id="fechas">
    </div>
</div>


<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>

<script type="text/javascript" charset="utf-8">
    function mostrarDetallesVenta (vid){
        window.location = "ventas.php?action=detalles&id=" + vid;
    }

    <?php


	
	for ($s=0; $s < sizeof($clientesRegistradosPorSucursal); $s++) { 

		$acc = 0;		
		echo "\nvar clientesEnSucursal". $s ." = [";

		for($i = 0; $i < sizeof($clientesRegistradosPorSucursal[$s]); $i++ ){
			$acc += $clientesRegistradosPorSucursal[$s][$i]["clientes"] ;
			echo  "[" . $i . "," . $acc . "]";

			if($i < sizeof($clientesRegistradosPorSucursal[$s]) - 1){
					echo ",";		
			}
		}
		echo "];\n";
	}
	

	echo "var fechas = [";
	for($i = 0; $i < sizeof($fechas); $i++ ){
		echo  "{ fecha : '" . $fechas[$i] . "'}";
		if($i < sizeof($fechas) - 1){
			echo ",";
		}
	}
	echo "];\n";

    ?>



	function meses(m){
		m = parseInt(m);
		switch(m){
			case 1: return "enero";
			case 2: return "febrero";
			case 3: return "marzo";
			case 4: return "abril";
			case 5: return "mayo";
			case 6: return "junio";
			case 7: return "julio";
			case 8: return "agosto";
			case 9: return "septiembre";
			case 10: return "octubre";
			case 11: return "noviembre";
			case 12: return "diciembre";
									
		}
	}
	
	
	
	Event.observe(document, 'dom:loaded', function() {
	    var graficaVentas = new HumbleFinance();
	
		graficaVentas.setXFormater(
				function(val){
					if(val ==0)return "";					
					return meses(fechas[val].fecha.split("-")[1]) + " "  + fechas[val].fecha.split("-")[2]; 
				}
			);

		graficaVentas.setYFormater(
				function(val){
					if(val ==0)return "";
					return val + " clientes";
				}
			);

		graficaVentas.setTracker(
			function (obj){
					obj.x = parseInt( obj.x );
					
					return meses(fechas[obj.x].fecha.split("-")[1]) + " "  + fechas[obj.x].fecha.split("-")[2]
								+ ", <b>"+ parseInt(obj.y) + "</b> clientes";

				}
			);
		<?php
			for ($s=0; $s < sizeof($clientesRegistradosPorSucursal); $s++) { 
				echo "graficaVentas.addGraph( clientesEnSucursal".$s." );";						
			}

		?>
	    graficaVentas.addSummaryGraph( clientesEnSucursal0 );
	    graficaVentas.render('finance');
	});


</script>










    

<br>

<style>
	.tab_holder{
		border-bottom:  1px solid #3F8CE9;
		font-weight: normal;
		font-size: 1.1em;
		color: white;
	}
	
	.tab_itself{
		background-color: #3F8CE9;;
		margin: 5px;
		padding-top: 5px;
		padding-left: 5px;
		padding-right: 5px;
		-moz-border-radius: 15px;
		
		border-top-left-radius: 5px;
		border-top-right-radius: 6px;		
	}
	
	.tab_itself.selected{
		background-color: blue;
	}
</style>
<!--
<div class="tab_holder">
	<span class="tab_itself">Todos los clientes</span>
	<span class="tab_itself selected">Clientes deudores</span>
	<span class="tab_itself">Nuevo cliente</span>
</div>
-->



<h2>Todos los clientes</h2>
<?php

//obtener los clientes del controller de clientes
$clientesFoo = listarClientes();
$clientes = array();

foreach($clientesFoo as $c){
	if($c["id_cliente"] <= 0)continue;
	array_push( $clientes, $c);
}

function sortClientesByName( $a, $b ){
	return strcasecmp($a["razon_social"] , $b["razon_social"]);
}

usort( $clientes, "sortClientesByName" );

//render the table
$header = array(  
		"razon_social" => "Razon Social", 
		"rfc" => "RFC", 
		"municipio" => "Municipio"  );

$tabla = new Tabla( $header, $clientes );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->addNoData("No hay clientes registrados.");
$tabla->render();




?>

<h2>Clientes deudores</h2> 
<?php

//obtener los clientes deudores del controller de clientes
$clientes = listarClientesDeudores();


function sortClientesByMoney( $a, $b ){
	return $a["saldo"] < $b["saldo"];
}

usort( $clientes, "sortClientesByMoney" );


//render the table
$header = array(  
	"razon_social" => "Nombre", 
	"rfc" => "RFC", 
	"municipio" => "Municipio",
	"saldo" => "Saldo" );

$tabla = new Tabla( $header, $clientes );
$tabla->addColRender( 'saldo', "moneyFormat" );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->addNoData("No hay clientes deudores.");
$tabla->render();



include ("admin/clientes.nuevo.php");

?>

