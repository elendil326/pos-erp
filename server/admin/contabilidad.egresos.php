<?php

require_once("controller/compras.controller.php");
require_once("controller/sucursales.controller.php");
require_once("controller/ventas.controller.php");
require_once("controller/personal.controller.php");
require_once("controller/efectivo.controller.php");
require_once("controller/inventario.controller.php");

require_once('model/pagos_venta.dao.php');
require_once('model/corte.dao.php');

?>

<script>
	jQuery("#MAIN_TITLE").html("EGRESOS");
</script>

<h2><img src='../media/icons/window_app_list_chart_32.png'>&nbsp;Egresos desde el ultimo corte</h2>


<?php


	if(POS_MULTI_SUCURSAL){
		$sucursal = SucursalDAO::getByPK($_REQUEST['id']);
	}else{
		$sucursal = SucursalDAO::getByPK(0);
	}


    $flujo = array();


	/*******************************************
	 * Fecha desde el ultimo corte
	 * *******************************************/
    $corte = new Corte();
	$corte->setIdSucursal($sucursal->getIdSucursal());

    $cortes = CorteDAO::getAll( 1, 1, 'fecha', 'desc' );



    if(sizeof($cortes) == 0){
	
		if(POS_MULTI_SUCURSAL){
			echo "<div class='light-blue-rounded' >No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.</div><br>";
		}else{
			echo "<div class='light-blue-rounded' >No se han hecho cortes. Mostrando flujo desde la apertura.</div><br>";
		}

        $fecha = $sucursal->getFechaApertura();

    }else{

        $corte = $cortes[0];
        echo "Fecha de ultimo corte: <b>" . $corte->getFecha() . "</b><br>";
        $fecha = $corte->getFecha();

    }
    

    $now = new DateTime("now");
    $hoy = $now->format("Y-m-d H:i:s");

	/*******************************************
	 * Buscar los gastos
	 * Buscar todos los gastos desde la fecha inicial
	 * *****************************************/
    $foo = new Gastos();
    $foo->setFecha( $fecha );
    $foo->setIdSucursal( $sucursal->getIdSucursal() );

    $bar = new Gastos();
    $bar->setFecha($hoy);
    
    $gastos = GastosDAO::byRange( $foo, $bar );


    foreach ($gastos as $g )
    {
        array_push( $flujo, array(
            "tipo" => "gasto",
            "concepto" => $g->getConcepto(),
            "monto" => $g->getMonto() * -1,
            "usuario" => $g->getIdUsuario(),
  			"fecha"=>$g->getFecha()
        ));
    }



	/*******************************************
	 * DIBUJAR LA GRAFICA
	 * *******************************************/
    $header = array(
               "tipo" => "Tipo",
               "concepto" => "Concepto",
               "usuario" => "Usuario",
               "fecha"=> "Fecha",
               "monto" => "Monto" );


    function renderMonto( $monto )
    {
        if($monto < 0 )
          return "<div style='color:red;'>" . moneyFormat($monto) ."</div>";
    
        return "<div style='color:green;'>" . moneyFormat($monto) ."</div>";
    }


function cmpFecha($a, $b)
{
    if ($a["fecha"] == $b["fecha"]) {
        return 0;
    }
    return ($a["fecha"] <$b["fecha"]) ? -1 : 1;
}
usort($flujo, "cmpFecha");

    $tabla = new Tabla($header, $flujo );
    $tabla->addColRender("usuario", "renderUsuario");
    $tabla->addColRender("fecha", "toDate");
    $tabla->addColRender("monto", "renderMonto");
    $tabla->addNoData("<div class='blue-rounded' >No hay egresos de efectivo.</div>");
    $tabla->render();

	$enCaja = 0;

	foreach($flujo as $f){
		$enCaja += $f['monto'];
	}
	
	if($enCaja != 0)
	echo "<div align=right><h3>Total en caja: " . moneyFormat($enCaja) . "</h3></div>";