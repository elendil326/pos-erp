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


<h2>Flujo de efectivo desde el ultimo corte</h2>

<script>
	jQuery("#MAIN_TITLE").html("Balance");
</script>
<?php

function renderUsuario($var) {
    return UsuarioDAO::getByPK($var)->getNombre();
}

if (POS_MULTI_SUCURSAL) {
    $sucursal = SucursalDAO::getByPK(0);
} else {
    $sucursal = SucursalDAO::getByPK(0);
}


$flujo = array();


/* * *****************************************
 * Fecha desde el ultimo corte
 * ****************************************** */
$corte = new Corte();
$corte->setIdSucursal($sucursal->getIdSucursal());

$cortes = CorteDAO::getAll(1, 1, 'fecha', 'desc');



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
 * Buscar los gastos
 * Buscar todos los gastos desde la fecha inicial
 * **************************************** */
$foo = new Gastos();
$foo->setFecha($fecha);
$foo->setIdSucursal($sucursal->getIdSucursal());

$bar = new Gastos();
$bar->setFecha($hoy);

$gastos = GastosDAO::byRange($foo, $bar);


foreach ($gastos as $g) {
    array_push($flujo, array(
        "tipo" => "gasto",
        "concepto" => $g->getConcepto(),
        "monto" => $g->getMonto() * -1,
        "usuario" => $g->getIdUsuario(),
        "fecha" => $g->getFecha(),
		"cargo" => $g->getMonto() * -1,
		"abono" => null,
		"saldo" => null
    ));
}


/* * *****************************************
 * Ingresos
 * Buscar todos los ingresos desde la fecha inicial
 * ****************************************** */
$foo = new Ingresos();
$foo->setFecha($fecha);
$foo->setIdSucursal($sucursal->getIdSucursal());

$bar = new Ingresos();
$bar->setFecha($hoy);

$ingresos = IngresosDAO::byRange($foo, $bar);

foreach ($ingresos as $i) {
    array_push($flujo, array(
        "tipo" => "ingreso",
        "concepto" => $i->getConcepto(),
        "monto" => $i->getMonto(),
        "usuario" => $i->getIdUsuario(),
        "fecha" => $i->getFecha(),
		"abono" => $i->getMonto(),
		"cargo" => null,
		"saldo" => null
    ));
}


/* * *****************************************
 * Ventas
 * Buscar todas la ventas a contado para esta sucursal desde esa fecha
 * ****************************************** */
$foo = new Ventas();
$foo->setFecha($fecha);
$foo->setIdSucursal($sucursal->getIdSucursal());
$foo->setTipoVenta('contado');

$bar = new Ventas();
$bar->setFecha($hoy);

$ventas = VentasDAO::byRange($foo, $bar);

$total_ventas = 0;
//las ventas
foreach ($ventas as $i) {
    array_push($flujo, array(
        "tipo" => "venta",
        "concepto" => "<a href='ventas.php?action=detalles&id=" . $i->getIdVenta() . "'>Venta de contado</a>",
        "monto" => $i->getPagado(),
        "usuario" => $i->getIdUsuario(),
        "fecha" => $i->getFecha(),
		"abono" => $i->getPagado(),
		"cargo" => null,
		"saldo" => null
    ));
    $total_ventas += $i->getPagado();
}

/* * *****************************************
 * Compras
 * Buscar todas la compras a contado para esta sucursal desde esa fecha
 * ****************************************** */
$foo = new CompraCliente();
$foo->setFecha($fecha);
$foo->setIdSucursal($sucursal->getIdSucursal());
$foo->setTipoCompra('contado');

$bar = new CompraCliente();
$bar->setFecha($hoy);

$compras = CompraClienteDAO::byRange($foo, $bar);

$total_compras = 0;

//las compras
foreach ($compras as $i) {
    array_push($flujo, array(
        "tipo" => "compra",
        "concepto" => "<a href='compras.php?action=detalleCompraCliente&id=" . $i->getIdCompra() . "'>Compra de contado</a>",
        "monto" => ($i->getPagado() * -1),
        "usuario" => $i->getIdUsuario(),
        "fecha" => $i->getFecha(),
		"cargo" => $i->getPagado() * -1,
		"abono" => null,
		"saldo" => null
    ));
    $total_compras += $i->getPagado();
}


/* * *****************************************
 * Abonos
 * Buscar todos los abonos para esta sucursal que se hicierond espues de esa fecha
 * ****************************************** */
$query = new PagosVenta();
$query->setIdSucursal($sucursal->getIdSucursal());
$query->setFecha($fecha);

$queryE = new PagosVenta();
$queryE->setFecha($hoy);


$results = PagosVentaDAO::byRange($query, $queryE);

foreach ($results as $pago) {
    array_push($flujo, array(
        "tipo" => "abono",
        "concepto" => "<a href='ventas.php?action=detalles&id=" . $pago->getIdVenta() . "'>Abono a venta</a>",
        "monto" => $pago->getMonto(),
        "usuario" => $pago->getIdUsuario(),
        "fecha" => $pago->getFecha(),
		"abono" => $i->getPagado(),
		"cargo" => null,
		"saldo" => null
    ));
}


/* * *****************************************
 * DIBUJAR LA GRAFICA
 * ****************************************** */
$header = array(
		"tipo" => "Tipo",
		"concepto" => "Concepto",
		"usuario" => "Usuario",
		"fecha" => "Fecha",
//		"monto" => "Monto",
		"abono"	=> "Abonos",
		"cargo" => "Cargos",
		"saldo" => "Saldo"
	);

function renderMonto($monto) {
	if($monto == null) return "";
    if ($monto < 0)
        return "<div style='color:red;'>" . moneyFormat($monto) . "</div>";

    return "<div style='color:green;'>" . moneyFormat($monto) . "</div>";
}

function cmpFecha($a, $b) {
    if ($a["fecha"] == $b["fecha"]) {
        return 0;
    }
    return ($a["fecha"] < $b["fecha"]) ? -1 : 1;
}

usort($flujo, "cmpFecha");

$saldo_inicial = 0;

function renderSaldo($n, $row){
	global $saldo_inicial;
	if($row["abono"] != null){
		//es un abono
		$saldo_inicial += abs($row["abono"]);
		
	}else{
		//es un cargo
		$saldo_inicial -= abs($row["cargo"]);
		
	}

	return renderMonto($saldo_inicial);
}

$tabla = new Tabla($header, $flujo);
$tabla->addColRender("usuario", "renderUsuario");
$tabla->addColRender("fecha", "toDate");

$tabla->addColRender("abono", "renderMonto");
$tabla->addColRender("cargo", "renderMonto");
$tabla->addColRender("saldo", "renderSaldo");
$tabla->addNoData("No hay operaciones.");
$tabla->render();

$enCaja = 0;

foreach ($flujo as $f) {
    $enCaja += $f['monto'];
}


?>
<div align=center style="margin-top: 15px">
	<div  class='blue-rounded' style='width: 300px;'>Saldo <?php echo moneyFormat($enCaja); ?> pesos </div>
</div>



<table style="margin-top:50px; width:100%">
	<tr>
		<td class="light-blue-rounded">
			<b>Total en compras</b>
			<?php echo moneyFormat($total_compras); ?>
		</td>
		<td class="light-blue-rounded">
			<b>Total en ventas</b>
			<?php echo moneyFormat($total_ventas); ?>
		</td>
		<td class="light-blue-rounded">
			<b>Total en bancos</b>
			<?php echo moneyFormat(0); ?>
		</td>
		<td class="light-blue-rounded">
			<b>Total en efectivo</b>
			<?php echo moneyFormat($total_compras); ?>
		</td>
	</tr>

	</table>

