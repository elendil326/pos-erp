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


<script>//document.getElementById("MAIN_TITLE").innerHTML = "Lista de clientes";</script>



<h2>Mapa de clientes</h2>
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

    //obtener la fecha de la sucursal que abrio primero
    $firstSuc = SucursalDAO::getAll(1, 1, 'fecha_apertura', 'ASC' );

    
    if(sizeof($firstSuc)!=0)
    {
    
            $numClientes = array();
            $fechas = array();
            $n = 0;
    		
            $primerCliente = $firstSuc[0]->getFechaApertura();
            
            $start = date("Y-m-d", strtotime("-1 day", strtotime($primerCliente)));
            $now = date ( "Y-m-d" );

		
			while( true ){

	            $v1 = new Cliente();
                $v1->setIdCliente("0");
	            $v1->setFechaIngreso( $start . " 00:00:00" );

	            $v2 = new Cliente();
                $v2->setIdCliente("999");
	            $v2->setFechaIngreso( $start . " 23:59:59" );				
	            
	            $results = ClienteDAO::byRange($v1, $v2);
                $n += count($results);	            
                
                array_push( $numClientes, $n );
                array_push( $fechas, $start );
                
   				if($start == $now){
   					break;
   				}
   				
  				$start = date("Y-m-d", strtotime("+1 day", strtotime($start)));
			}
			

            echo "\nvar numClientes = [";
            for($i = 0; $i < sizeof($numClientes); $i++ ){
                echo  "[" . $i . "," . $numClientes[$i] . "]";
                if($i < sizeof($numClientes) - 1){
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
		if(window.numClientes){
		    var graficaVentas = new HumbleFinance();
		    graficaVentas.addGraph( numClientes );
		    graficaVentas.addSummaryGraph( numClientes );
		    graficaVentas.render('finance');		
		}else{
			$('finance').innerHTML = "No hay clientes";
		}

        
         

	});

</script>










    

<br>
<h2><!-- <img src='../media/icons/users_32.png'>&nbsp; -->Todos los clientes</h2><?php
//obtener los clientes del controller de clientes
$clientes = listarClientes();



function sortClientes( $a, $b ){
	
	return strcasecmp($a["nombre"] , $b["nombre"]);
	
}

usort( $clientes, "sortClientes" );

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", /* "direccion" => "Direccion",*/  "ciudad" => "Ciudad"  );
$tabla = new Tabla( $header, $clientes );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->addNoData("No hay clientes registrados.");
$tabla->render();




?>

<h2><!-- <img src='../media/icons/user_warning_32.png'>&nbsp;-->Clientes deudores</h2> <?php

//obtener los clientes deudores del controller de clientes
$clientes = listarClientesDeudores();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", /* "direccion" => "Direccion", */ "saldo" => "Saldo" );

$tabla = new Tabla( $header, $clientes );
$tabla->addColRender( 'saldo', "moneyFormat" );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->addNoData("No hay clientes deudores.");
$tabla->render();



include ("admin/clientes.nuevo.php");

?>

