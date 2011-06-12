<?php


require_once("controller/ventas.controller.php");
require_once("controller/clientes.controller.php");
require_once("model/cliente.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/usuario.dao.php");




/**
 * Vamos a ver si mando el id de la venta
 * 
 * */
if(!isset($_REQUEST['id'])){
	Logger::log("!!!!! CLIENTE HA SOLICITADO VENTA QUE NO ES DE EL !!!!!");
	Logger::log("CLIENTE:" . $_SESSION["cliente_id"]);
	
	?><script>window.location = '.';</script><?php
	
	return;	
}


$detalles = detalleVenta($_REQUEST['id']);

$venta = $detalles['detalles'];


if(!$venta){
	Logger::log("!!!!! CLIENTE HA SOLICITADO VENTA QUE NO ES DE EL !!!!!");
	Logger::log("CLIENTE:" . $_SESSION["cliente_id"]);
	
	?><script>window.location = '.';</script><?php
	
	return;
}

/**
 * Antes que nada vamos a ver si esta venta 
 * si es de este cliente
 * 
 * */
if($venta->getIdCliente() != $_SESSION["cliente_id"]){
	Logger::log("!!!!! CLIENTE HA SOLICITADO VENTA QUE NO ES DE EL !!!!!");
	Logger::log("CLIENTE:" . $_SESSION["cliente_id"]);
	
	?><script>window.location = '.';</script><?php
	
	return;	
}


?><h2>Detalles</h2>

<script>
	jQuery("#MAIN_TITLE").html( "Detalles de la venta");
</script>

<table cellspacing="2" cellpadding="2" border=0 style="width:100%">
    <tr>
        <td ><b>ID Venta</b></td>
        <td><?php echo $venta->getIdVenta(); ?></td>
        <td><b>Subtotal</b></td>
        <td><?php echo moneyFormat($venta->getSubtotal()); ?></td>
    </tr>

    <tr>
        <td><b>Cliente</b></td>
        <td><?php
            if($venta->getIdCliente() < 0){
                echo "Caja Comun";
            }else{
                echo ClienteDAO::getByPK( $venta->getIdCliente() )->getRazonSocial();
            }

        ?></td>
		    <td><b>Descuento</b></td>
	        <td><?php echo percentFormat($venta->getDescuento()); ?></td>

    </tr>

    <tr>
        <td><b>Tipo Venta</b></td>
        <td><?php echo strtoupper($venta->getTipoVenta()); ?></td>
        <td><b>Total</b></td>
        <td><?php echo moneyFormat($venta->getTotal()); ?></td>
    </tr>

    <tr>
        <td><b>Fecha</b></td>
        <td><?php echo toDate($venta->getFecha()); ?></td>
        <td><b>Pagado</b></td>
        <td><?php echo moneyFormat($venta->getPagado()); ?></td>
    </tr>





    <tr>
        <td><b>Sucursal</b></td>
        <td><?php 

			$suc = SucursalDAO::getByPK( $venta->getIdSucursal() );

			if($suc)
				echo $suc->getDescripcion();
			else 
				echo "Sucursal invalida";
        ?></td>

	    <?php if($venta->getTipoVenta() == 'credito'){ ?>
	        <td><b>Saldo pendiente</b></td>
	        <td><b style="color: red"><?php echo moneyFormat($venta->getPagado()-$venta->getTotal()); ?></b></td>
	    <?php } ?>
    </tr>

    <tr>
        <td><b>Cajero</b></td>
        <td><?php 
            echo UsuarioDAO::getByPK( $venta->getIdUsuario() )->getNombre();
        ?></td>
    </tr>



</table>


<h2>Articulos en la venta</h2><?php

function renderProd($qty, $row){
	if($qty == 0) return "";
	return number_format($qty, 2) . "&nbsp;" . $row['escala'] . "s";
}

function renderMoney($money, $row){
	if($money == 0) return "";
	return moneyFormat($money) ;
}

//buscar si el prodcuto es procesable


//render the table
$header = array( 
	"id_producto" => "ID", 
	"descripcion" => "Descripcion", 
	"cantidad" => "Cantidad original",
	"precio" => "Precio original",
	"cantidadProc" => "Cantidad procesada",
	"precioProc" => "Precio procesada" ,
	"descuento" => "Descuento" );

/*echo "<pre>";
var_dump($detalles);
var_dump($venta);
echo "</pre>";*/

$tabla = new Tabla( $header, $detalles['items'] );
$tabla->addColRender( 'precio', "renderMoney" );
$tabla->addColRender( 'precioProc', "renderMoney" );
$tabla->addColRender( 'cantidad', 'renderProd');
$tabla->addColRender( 'cantidadProc', 'renderProd');
$tabla->addColRender( 'descuento', 'renderProd');
$tabla->render();








if($venta->getTipoVenta() == 'credito'){
    ?><h2>Abonos a esta venta</h2><?php

    $abonos = listarAbonos($venta->getIdCliente(), $venta->getIdVenta() );

    $header = array( 
	    "id_pago" => "Pago", 
	    "id_venta" => "Venta", 
	    "sucursal" => "Sucursal",
	    "cajero" => "Cajero",
	    "fecha" => "Fecha",
	    "monto" => "Monto" );

    $tabla = new Tabla( $header, $abonos );
    $tabla->addColRender( 'precio', "moneyFormat" );
    $tabla->addColRender( 'monto', "moneyFormat" );
    $tabla->addColRender( 'fecha', "toDate" );
	$tabla->addNoData("No se han hecho abonos a esta venta");
    $tabla->render();
}

?>







<script>
	function printComprobante(){
		window.location = "../proxy.php?action=1306&id_venta=<?php echo $_REQUEST['id']; ?>" ;
	}

	function printFactura(){
		window.location = "../proxy.php?action=1305&id_venta=<?php echo $_REQUEST['id']; ?>";
	}
</script>


<h4 id="submitButtons">
	<input type=button value="Imprimir comprobante" onClick="printComprobante()">
	<?php
		if($venta->getLiquidada() && !$venta->getCancelada()){
			$q = new FacturaVenta();
			$q->setIdVenta( $venta->getIdVenta() );
			$res = FacturaVentaDAO::search(  $q  );
		
			if(sizeof($res) == 0){
				//no se ha hecho factura
				/*
				?>
				<input type="button" value="Solicitar esta factura" onClick='printFactura()' >
				<?php
				*/
			}else{
				//ya se ha hecho factura !
				?><input type="button" value="Reimprimir esta factura" onClick='printFactura()' ><?php

			}
		}
	?>
</h4>
<div id="loader" 		style="display: none;" align="center"  >
	Procesando <img src="../media/loader.gif"> 
</div>