<?php


require_once("controller/clientes.controller.php");
require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");

?>


<script type="text/javascript" charset="utf-8" src="../frameworks/prototype/prototype.js"></script>
<script src="../frameworks/humblefinance/flotr/flotr.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/excanvas.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvastext.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/canvas2image.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/humblefinance/flotr/base64.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8" src="../frameworks/humblefinance/humble/HumbleFinance.js"></script>
<link rel="stylesheet" href="../frameworks/humblefinance/humble/finance.css" type="text/css" media="screen" title="no title" charset="utf-8">

<h1>Clientes</h1>



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
    
    if(sizeof($firstSuc)!=0){
            $primerCliente = $firstSuc[0]->getFechaApertura();
            $date = new DateTime($primerCliente);

            $now = new DateTime("now");
            $offset = $date->diff($now);


            $numClientes = array();
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

	            $v1 = new Cliente();
                $v1->setIdCliente("0");
	            $v1->setFechaIngreso( $date->format('Y-m-d H:i:s') );


	            $date->setTime ( 23, 59, 59 );
	            $v2 = new Cliente();
                $v2->setIdCliente("999");
	            $v2->setFechaIngreso( $date->format('Y-m-d H:i:s') );

	            $results = ClienteDAO::byRange($v1, $v2);
                $n += count($results);
                array_push( $numClientes, $n );


                array_push( $fechas, $date->format('Y-m-d') );

                //siguiente dia
                $date->add( new DateInterval("P1D") );
                $offset = $date->diff($now);
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


	    HumbleFinance.trackFormatter = function (obj) {
            return fechas[ parseInt(obj.x) ].fecha + "\nClientes:" + parseInt(obj.y) ;

	    };

	    HumbleFinance.yTickFormatter = function (n) {
	        if (n == this.axes.y.max) {
	            return false;
	        }

            if(n == 0 ){
                return false;
            }

	        return n + " clientes";
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

	    HumbleFinance.init('finance', numClientes, [], numClientes);
		

		
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










    


<h2><img src='../media/icons/users_32.png'>&nbsp;Todos los clientes</h2><?php
//obtener los clientes del controller de clientes
$clientes = listarClientes();



//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "ciudad" => "Ciudad"  );
$tabla = new Tabla( $header, $clientes );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->addNoData("No hay clientes registrados.");
$tabla->render();




?>

<h2><img src='../media/icons/user_warning_32.png'>&nbsp;Clientes deudores</h2> <?php

//obtener los clientes deudores del controller de clientes
$clientes = listarClientesDeudores();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "saldo" => "Saldo" );

$tabla = new Tabla( $header, $clientes );
$tabla->addColRender( 'saldo', "moneyFormat" );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->addNoData("No hay clientes deudores.");
$tabla->render();


?>

