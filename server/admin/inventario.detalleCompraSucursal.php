<?php
	require_once('model/proveedor.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/compra_sucursal.dao.php');
    require_once('model/detalle_compra_sucursal.dao.php');
    require_once('model/inventario_maestro.dao.php');

	require_once("controller/inventario.controller.php");

    $compra 	= 	CompraSucursalDAO::getByPK		( $_REQUEST['cid'] );
?>



<script type="text/javascript" charset="utf-8">


	
</script>

<h2>Detalles de la compra de esta sucursal</h2>
	<table border="0" cellspacing="5" cellpadding="5" style="width: 100%">
		<tr><td>Fecha de compra</td>			<td><?php echo toDate($compra->getFecha()); ?></td></tr>
		<tr><td>Proveedor</td>					<td>Centro de distribucion</td></tr>

		<tr><td>Sucursal</td>	<td><?php echo $compra->getIdSucursal();?></td></tr>
		<tr><td>Total</td>	<td><?php echo moneyFormat( $compra->getTotal() ); ?></td></tr>
		<tr><td>Pagado</td>	<td><?php echo moneyFormat( $compra->getPagado() );?></td></tr>
		<tr><td>Saldo</td>	<td> - <b><?php echo moneyFormat($compra->getTotal()  - $compra->getPagado()); ?></b></td></tr>

	</table>



<h2>Detalles de esta compra </h2>
<?php



function toUnit( $e )
{
	return "<b>" . number_format($e, 2) . "</b>kg";
}

	
	$query = new DetalleCompraSucursal();
	$query->setIdCompra( $_REQUEST["cid"] );

	$detalles = DetalleCompraSucursalDAO::search( $query );
	
	function renderProd($pid){
		
		$foo = InventarioDAO::getByPK( $pid );
		return $foo->getDescripcion();
	}
	
	
	function renderProc($proc){
		if($proc){
			return "Si";
		}else{
			return "No";
		}
	}

	$header = array(
		"id_producto" => "Producto",
		"cantidad" => "Cantidad",
		"precio" => "Precio",
		"descuento" => "Descuento",
		"procesadas" => "procesada" );

	$tabla = new Tabla($header, $detalles);
	$tabla->addColRender("precio", "moneyFormat");
	$tabla->addColRender("cantidad", "toUnit");
	$tabla->addColRender("descuento", "toUnit");	
	$tabla->addColRender("procesada", "toUnit");	
	$tabla->render();
?>







