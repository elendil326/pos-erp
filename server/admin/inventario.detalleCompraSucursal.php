<?php
	require_once('model/proveedor.dao.php');
    require_once('model/inventario.dao.php');
    require_once('model/compra_sucursal.dao.php');
	require_once('model/sucursal.dao.php');
    require_once('model/detalle_compra_sucursal.dao.php');
    require_once('model/inventario_maestro.dao.php');

	require_once("controller/inventario.controller.php");

    $compra 	= 	CompraSucursalDAO::getByPK		( $_REQUEST['cid'] );
?>



<script type="text/javascript" charset="utf-8">


	
</script>

<h2>Detalles de la compra de esta sucursal</h2>
	<table border="0" cellspacing="2" cellpadding="2" style="width: 100%">
		<tr><td>Fecha de compra</td>			<td><?php echo toDate($compra->getFecha()); ?></td>
			<td>Total</td>	<td><?php echo moneyFormat( $compra->getTotal() ); ?></td></tr>
		
		<tr><td>Proveedor</td>					<td>Centro de distribucion</td>
			<td>Pagado</td>	<td><?php echo moneyFormat( $compra->getPagado() );?></td></tr>
		
		<tr><td>Sucursal</td>	<td><?php 
										$c = SucursalDAO::getByPK($compra->getIdSucursal());
										echo $c->getDescripcion();
									?></td>
			<td>Saldo</td>	<td> - <b><?php echo moneyFormat($compra->getTotal()  - $compra->getPagado()); ?></b></td></tr>
		
	</table>



<h2>Detalles de esta compra </h2>
<?php



function toUnit( $e, $row )
{
	//unidaes del producto
	$producto = InventarioDAO::getByPK( $row['id_producto'] );
	
	$return = $e . " " . $producto->getEscala() . "s ";
			
	if($producto->getAgrupacion()){
		//tiene agrupacion
		$return .= "<i>( " . ($e / $producto->getAgrupacionTam()) . " " . $producto->getAgrupacion() . "s )</i>" ;
		
	}else{
		//no tiene agrupacion, solo mostrar la escala
		
	}
	
	//buscar este producto
	return $return;
	
}

function toUnitDesc( $e )
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
		"id_producto" 	=> "Producto",
		"procesadas" 	=> "Procesada",
		"cantidad" 		=> "Cantidad",
		"precio" 		=> "Precio",
		"descuento" 	=> "Descuento"  );

	$tabla = new Tabla($header, $detalles);
	$tabla->addColRender("precio", 		"moneyFormat");
	$tabla->addColRender("cantidad", 	"toUnit");
	$tabla->addColRender("id_producto", "renderProd");
	$tabla->addColRender("descuento", 	"toUnitDesc");	
	$tabla->addColRender("procesadas", "renderProc");	
	$tabla->render();
	
	
?>


<script>
	<?php
		//please print
		if(isset($_REQUEST["pp"]) && $_REQUEST["pp"]){
			?>
				Ext.Msg.confirm("Surtir sucursal",
				"Se ha surtido con exito esta sucursal. &iquest; Desea imprimir un reporte ?",
				function(res){

					if(res == "yes"){
						window.print();
					}
				} )
			<?php
		}
	?>
</script>




