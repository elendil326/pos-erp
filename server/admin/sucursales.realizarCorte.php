<?php

	require_once("model/sucursal.dao.php");
	require_once("model/pagos_compra.dao.php");	
	require_once("controller/clientes.controller.php");

	require_once("controller/compras.controller.php");
	require_once("controller/sucursales.controller.php");
	require_once("controller/ventas.controller.php");
	require_once("controller/personal.controller.php");
	require_once("controller/efectivo.controller.php");
	require_once("controller/inventario.controller.php");

	require_once('model/pagos_venta.dao.php');
	require_once('model/corte.dao.php');
?>


<h2>Flujo de efectivo desde el ultimo corte</h2>

<script>
	jQuery("#MAIN_TITLE").html("Corte de sucursal");
</script>
<?php

function renderUsuario($var) {
    return UsuarioDAO::getByPK($var)->getNombre();
}

if (POS_MULTI_SUCURSAL) {
    $sucursal = SucursalDAO::getByPK( $_GET["id_sucursal"] );
} else {
    $sucursal = SucursalDAO::getByPK( 0 );
}


$flujo = array();


/* * *****************************************
 * Fecha desde el ultimo corte
 * ****************************************** */
$corte = new Corte();
$corte->setIdSucursal($sucursal->getIdSucursal());

$cortes = CorteDAO::getAll(1, 1, 'fecha', 'desc');

	/* ***** Este Corte ********** */
	$esteCorte = new Corte();
	
	/* ***** Este Corte ********** */
	$esteCorte->setIdSucursal($sucursal->getIdSucursal());


	if (sizeof($cortes) == 0) {
	    if(POS_MULTI_SUCURSAL){
			echo "<div class='light-blue-rounded' >No se han hecho cortes en esta sucursal. Mostrando flujo desde la apertura de sucursal.</div><br>";
		}else{
			echo "<div class='light-blue-rounded' >No se han hecho cortes. Mostrando flujo desde la apertura.</div><br>";
		}

	    $fecha = $sucursal->getFechaApertura();
	} else {

	    $corte = $cortes[0];
	    echo "Fecha de ultimo corte: <b>" . $corte->getFecha() . "</b><br>";
	    $fecha = $corte->getFecha();
	}


	$now = new DateTime("now");
	$hoy = $now->format("Y-m-d H:i:s");



	/* * *****************************************
	 * Total de ventas
	 * total de activo realizado en ventas para 
	 * esta sucursal incluyendo ventas a credito 
	 * y ventas a contado aunque no esten saldadas
	 * **************************************** */

	/* ***** Este Corte ********** */
	$esteCorte->setTotalVentas( 
			VentasDAO::totalVentasDesdeFecha( $sucursal->getIdSucursal(), $fecha )
		 );
		

	/* * *****************************************
	 * Total de ventas Abonado
	 * total de efectivo adquirido gracias a ventas, 
	 * incluye ventas a contado y los abonos de las 
	 * ventas a credito
	 * **************************************** */

	//obtener todas la ventas a contado
	$ventas_a_contado = 0;
	
		$foo = new Ventas();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($sucursal->getIdSucursal());
		$foo->setTipoVenta('contado');

		$bar = new Ventas();
		$bar->setFecha($hoy);

		$ventas = VentasDAO::byRange($foo, $bar);

		foreach ($ventas as $i) {
		    $ventas_a_contado += $i->getPagado();
		}
	
	
	//obtener todos los abonos
	$abonos_a_creditos = 0;
	
		$query = new PagosVenta();
		$query->setIdSucursal($sucursal->getIdSucursal());
		$query->setFecha($fecha);

		$queryE = new PagosVenta();
		$queryE->setFecha($hoy);

		$results = PagosVentaDAO::byRange($query, $queryE);

		foreach ($results as $pago) {
			$abonos_a_creditos += $pago->getMonto();
		}

	/* ***** Este Corte ********** */
	$esteCorte->setTotalVentasAbonado( 
			$abonos_a_creditos + $ventas_a_contado
		 );

		
	/* * *****************************************
	 * total ventas saldo
	 * total de dinero que se le debe a esta sucursal
	 * por ventas a credito
	 * **************************************** */		
	$foo = new Ventas();
	$foo->setIdSucursal($sucursal->getIdSucursal());
	$foo->setTipoVenta("credito");
	$foo->setFecha($fecha);
	$foo->setLiquidada(0);
	
	$bar = new Ventas();
	$bar->setFecha($hoy);
	
	$res = VentasDAO::byRange($foo, $bar);
	
	$saldo_pendiente = 0;
	
	foreach ($res as $venta) {
		$saldo_pendiente += ($venta->getTotal() - $venta->getPagado());
	}
	
	
	/* ***** Este Corte ********** */
	$esteCorte->setTotalVentasSaldo( 
			$saldo_pendiente
		 );
	
	/* * *****************************************
	 * Total Compras
	 * total de gastado en compras
	 * **************************************** */
	$foo = new CompraSucursal();
	$foo->setFecha($fecha);
	$foo->setIdSucursal($sucursal->getIdSucursal());

	$bar = new CompraSucursal();
	$bar->setFecha($hoy);

	$compras = CompraSucursalDAO::byRange($foo, $bar);

	$total_compras = 0;

	//las compras
	foreach ($compras as $i) {
	    $total_compras += $i->getTotal();
	}
	
	/* ***** Este Corte ********** */
	$esteCorte->setTotalCompras( 
			$total_compras
		 );
	
	
	/* * *****************************************
	 * Total Compras Abonado
	 * total de abonado en compras
	 * **************************************** */	
	$foo = new PagosCompra();
	$foo->setFecha($fecha);
	//$foo->setIdSucursal($sucursal->getIdSucursal());

	$bar = new PagosCompra();
	$bar->setFecha($hoy);

	$compras = PagosCompraDAO::byRange($foo, $bar);

	$total_compras_pagadas = 0;

	//las compras
	foreach ($compras as $i) {
	    $total_compras_pagadas += $i->getMonto();
	}
	
	/* ***** Este Corte ********** */
	$esteCorte->setTotalComprasAbonado( 
			$total_compras_pagadas
		 );
		
		
	

	/* * *****************************************
	 * Total Gastos
	 * total de gastos con saldo o sin salgo
	 * **************************************** */	
		$foo = new Gastos();
		$foo->setFecha($fecha);
		$foo->setIdSucursal($sucursal->getIdSucursal());

		$bar = new Gastos();
		$bar->setFecha($hoy);

		$gastos = GastosDAO::byRange($foo, $bar);

		$total_gastos = 0;
		
		foreach ($gastos as $g) {
			$total_gastos += $g->getMonto();
		}
		
	/* ***** Este Corte ********** */
	$esteCorte->setTotalGastos( 
			$total_gastos
		 );	
	
	
	/* ***** Este Corte ********** */
	$esteCorte->setTotalGastosAbonado( 0 );
	
	
	/* ***** Este Corte ********** */
	$esteCorte->setTotalIngresos( 0 );
	
	
	/* ***** Este Corte ********** */
	$esteCorte->setTotalGananciaNeta( 0 );
	
	
	
	
	
	echo valorDelInventarioActual( $sucursal->getIdSucursal() );
	
	?>

	
<table style="margin-top:25px; width:100%" border=1>
	<tr>
		<td>total_ventas</td>
		<td>total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas</td>
		<td><?php echo moneyFormat($esteCorte->getTotalVentas()); ?></td>
	</tr>
	<tr>
		<td>total_ventas_abonado</td>
		<td>total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito</td>
		<td><?php echo moneyFormat($esteCorte->getTotalVentasAbonado()); ?></td>
	</tr>
	<tr>
		<td>total_ventas_saldo</td>
		<td>total de dinero que se le debe a esta sucursal por ventas a credito</td>		
		<td><?php echo moneyFormat($esteCorte->getTotalVentasSaldo()); ?></td>
	</tr>
	<tr>
		<td>total_compras</td>
		<td>total de gastado en compras</td>		
		<td><?php echo moneyFormat($esteCorte->getTotalCompras()); ?></td>
	</tr>
	<tr>
		<td>total_compras_abonado</td>
		<td>total de abonado en compras</td>		
		<td><?php echo moneyFormat($esteCorte->getTotalComprasAbonado()); ?></td>
	</tr>			
	<tr>
		<td>total_gastos</td>
		<td>total de gastos con saldo o sin salgo</td>		
		<td><?php echo moneyFormat($esteCorte->getTotalGastos()); ?></td>
	</tr>
	<tr>
		<td>total_ingresos</td>
		<td>total de ingresos para esta sucursal desde el ultimo corte</td>		
		<td><?php echo moneyFormat($esteCorte->getTotalIngresos()); ?></td>
	</tr>
	<tr>
		<td>total_ganancia_neta</td>
		<td>calculo de ganancia neta</td>		
		<td><?php echo moneyFormat($esteCorte->getTotalGananciaNeta()); ?></td>
	</tr>				
</table>



<h4 >
	<div class="hide_on_ajax">
		<input type="button"  value="realizar corte" onclick="sendCorte()">
	</div>
    <div id="loader" 		style="display: none;" align="center"  >
		Realizando venta <img src="../media/loader.gif">
    </div>
</h4>

<script type="text/javascript" charset="utf-8">
function sendCorte(){
	
	jQuery(".hide_on_ajax").fadeOut("fast", function(){
		jQuery("#loader").fadeIn();		
		jQuery.ajax({
	            url: "../proxy.php",
	            data: { 
	                action : 708, 
	                id_sucursal : <?php echo $_GET["id_sucursal"]?>,
	            },
	            cache: false,
	            success: function(data){

	                try{
	                    response = jQuery.parseJSON(data);
	                    //console.log(response, data.responseText)
	                }catch(e){

	                    jQuery("#loader").fadeOut('slow', function(){
	                        window.scroll(0,0);                         
	                        jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                        jQuery(".hide_on_ajax").fadeIn();
	                    });                
	                    return;                    
	                }

                    jQuery("#loader").fadeOut('slow', function(){
                        jQuery(".hide_on_ajax").fadeIn();
                    });


	                if(response.success === false){

	                    if(response.reason){
	                        jQuery("#ajax_failure").html(response.reason).show();							
	                    }else{
	                        jQuery("#ajax_failure").html("Error en el servidor, porfavor intente de nuevo").show();
	                    }


	            		window.scroll(0,0);
	                    return ;
	                }

					window.location = "sucursales.php?action=cortesLista"
					console.log("OK !");

	            }
	        });
	});

	
	
}
</script>