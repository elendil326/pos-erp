<?php


	require_once('model/compra_proveedor.dao.php');
	require_once('model/compra_proveedor_flete.dao.php');	
	require_once('model/detalle_compra_proveedor.dao.php');	
	require_once('model/proveedor.dao.php');

	$compra = CompraProveedorDAO::getByPK( $_REQUEST['cid'] );
	$flete  = CompraProveedorFleteDAO::getByPK($_REQUEST['cid']);
	
	$proveedor = ProveedorDAO::getByPK( $compra->getIdProveedor() );
	
	$foo = new DetalleCompraProveedor();
	$foo->setIdCompraProveedor($_REQUEST['cid']);
	$detalles = DetalleCompraProveedorDAO::search( $foo );
	
?>
	<h2>Detalles del embarque</h2>
	<table style='width:100%' border=0>
		<tr><td colspan=2 ><h3>Detalles del producto</h3></td>
			<td colspan=2 ><h3>Detalles del flete</h3></td></tr>
			
		<tr><td>Remision</td>			<td><?php echo $compra->getFolio(); ?></td>
			<td>Nombre del chofer</td>	<td><?php echo $flete->getChofer(); ?></td></tr>
		
		<tr><td>Fecha de origen</td>	<td><?php echo $compra->getFechaOrigen(); ?></td>
			<td>Placas</td>				<td><?php echo $flete->getPlacasCamion(); ?></td></tr>
			
		<tr><td>Total arpillas</td>		<td><?php echo $compra->getArpillas(); ?></td>
			<td>Marca camion</td>		<td><?php echo $flete->getMarcaCamion(); ?></td></tr>
			
		<tr><td>Merma por arpilla</td>	<td><?php echo $compra->getMermaPorArpilla(); ?></td>
			<td>Modelo camion</td>		<td><?php echo $flete->getModeloCamion(); ?></td></tr>
			
		<tr><td>Numero de viaje</td>	<td><?php echo $compra->getNumeroDeViaje(); ?></td>
			<td>Costo total del flete</td><td><?php echo moneyFormat( $flete->getCostoFlete() ); ?></td></tr>
			
		<tr><td>Peso por arpilla</td>	<td><?php echo $compra->getPesoPorArpilla(); ?></td></tr>	
		
		<tr><td>Peso origen</td>		<td><?php echo $compra->getPesoOrigen(); ?></td></tr>	
				 
		<tr><td>Peso recibido</td>		<td><?php echo $compra->getPesoRecibido(); ?></td></tr>
		
		<!-- <tr><td>Peso real</td>			<td><?php  ?></td></tr> -->
		
		<tr><td>Productor</td>			<td><?php echo $compra->getProductor(); ?></td></tr>	
	</table>
	
	<h2>Productos en el embarque</h2>
	
	<?php
	
	
function toUnit( $e )
{
	return "<b>" . number_format($e, 2) . "</b>kg";
}
	
		$header = array( 
			"id_producto" 			=> "Producto",
			"variedad" 				=> "Variedad",
			"arpillas" 				=> "Arpillas",
			"kg" 					=> "Peso",
			"precio_por_kg" 		=> "Precio / KG");

		
		$t = new Tabla($header, $detalles);
		$t->addColRender("precio_por_kg", "moneyFormat");
		$t->addColRender("kg", "toUnit");	
		$t->render();
	?>
	
	
	
	
