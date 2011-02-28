<?php


	require_once('model/compra_proveedor.dao.php');
	require_once('model/compra_proveedor_flete.dao.php');	
	require_once('model/detalle_compra_proveedor.dao.php');	
	require_once('model/proveedor.dao.php');
	require_once('model/inventario.dao.php');

	

	$compra = CompraProveedorDAO::getByPK( $_REQUEST['cid'] );
	$flete  = CompraProveedorFleteDAO::getByPK($_REQUEST['cid']);
	
	$proveedor = ProveedorDAO::getByPK( $compra->getIdProveedor() );
	
	$foo = new DetalleCompraProveedor();
	$foo->setIdCompraProveedor($_REQUEST['cid']);
	$detalles = DetalleCompraProveedorDAO::search( $foo );
	
	

?>

	<script>
		jQuery("#MAIN_TITLE").html("Detalles del embarque <?php echo $compra->getFolio(); ?>");
	</script>

	<table style='width:100%' border=0>
		<tr><td colspan=2 ><h3>Detalles del producto</h3></td>
			<td colspan=2 ><h3>Detalles del flete</h3></td></tr>
			
		<tr><td>Remision</td>			<td><?php echo $compra->getFolio(); ?></td>
			<td>Nombre del chofer</td>	<td><?php echo $flete->getChofer(); ?></td></tr>
		
		<tr><td>Fecha de origen</td>	<td><?php echo toDate($compra->getFechaOrigen()); ?></td>
			<td>Placas</td>				<td><?php echo $flete->getPlacasCamion(); ?></td></tr>
			
		<tr><td>Total arpillas</td>		<td><?php echo $compra->getArpillas(); ?></td>
			<td>Marca camion</td>		<td><?php echo $flete->getMarcaCamion(); ?></td></tr>
			
		<tr><td>Merma por arpilla</td>	<td><?php echo $compra->getMermaPorArpilla(); ?></td>
			<td>Modelo camion</td>		<td><?php echo $flete->getModeloCamion(); ?></td></tr>
			
		<tr><td>Numero de viaje</td>	<td><?php echo $compra->getNumeroDeViaje(); ?></td>
			<td>Costo total del flete</td><td><?php echo moneyFormat( $flete->getCostoFlete() ); ?></td></tr>
			
		<tr><td>Peso por arpilla</td>	<td><b><?php echo number_Format($compra->getPesoPorArpilla(), 4); ?></b> Kg / Arpilla</td></tr>	
		
		<tr><td>Peso origen</td>		<td><?php echo number_Format($compra->getPesoOrigen(),2); ?></td></tr>	
				 
		<tr><td>Peso recibido</td>		<td><?php echo number_Format($compra->getPesoRecibido(),2); ?></td></tr>
		
		<!-- <tr><td>Peso real</td>			<td><?php  ?></td></tr> -->
		
		<tr><td>Productor</td>			<td><?php echo $compra->getProductor(); ?></td></tr>	
	</table>
	
	<h2>Productos en el embarque</h2>
	
	<?php
	
	
function toUnit( $e )
{
	return "<b>" . number_format($e, 2) . "</b> Kgs";
}

function renderProd( $pid ){
	$producto = InventarioDAO::getByPK( $pid );
	return $producto->getDescripcion();
}

$header = array( 
	"id_producto" 			=> "Producto",
	"variedad" 				=> "Variedad",
	"arpillas" 				=> "Arpillas",
	"kg" 					=> "Peso",
	"precio_por_kg" 		=> "Precio / KG");


$t = new Tabla($header, $detalles);
$t->addColRender("precio_por_kg", "moneyFormat");
$t->addColRender("id_producto", "renderProd");		

$t->addColRender("kg", "toUnit");	

echo "<div id='productos'>";
$t->render();
echo "</div>";

$totalKilos = 0;
$totalImporte = 0;
$totalArpillas = 0;

foreach( $detalles as $d ){
	$totalKilos += $d->getKg();
	$totalImporte += $d->getKg() * $d->getPrecioPorKg();
	$totalArpillas += $d->getArpillas();
}



?>
<script>
	jQuery("#productos table tbody").after("<tr style='color:#3F8CE9 '><td colspan=4></td><td style='border-top: 1px solid #3F8CE9; color:#3F8CE9 '><?php echo moneyFormat($flete->getCostoFlete() + $totalImporte); ?> Total</td></tr>");	
	jQuery("#productos table tbody").after("<tr style='color:#3F8CE9 '><td colspan=4></td><td><?php echo moneyFormat($flete->getCostoFlete()); ?> Flete</td></tr>");	
	jQuery("#productos table tbody").after("<tr style='border-top: 1px solid #3F8CE9; color:#3F8CE9 '><td colspan=2></td><td><?php echo $totalArpillas; ?></td><td><?php echo number_format($totalKilos, 2); ?></td><td><?php echo moneyFormat($totalImporte); ?></td></tr>");

</script>


<?php

if(isset($_REQUEST['askForPrint'])){
	?>
	<script>
		function printme(){
			jQuery(".success").hide();
			window.print();
			
		}
	
		var html = '<div align=center><h1>Caramento registrado existosamente</h1>';
		html += "&iquest; Desea imprimir un comprobante ?";
		html += "<br><br><input type='button' value='Si' onclick='jQuery(document).trigger(\"close.facebox\"); setTimeout(\"printme()\", 750)'><input type='button' value='No' onclick='jQuery(document).trigger(\"close.facebox\");'></div>"
		jQuery.facebox( html );
	</script>
	<?php
}

?>