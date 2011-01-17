<?php

	require_once('model/proveedor.dao.php');
	require_once('model/compra_proveedor.dao.php');
	require_once('model/detalle_compra_proveedor.dao.php');
	require_once('model/compra_proveedor_flete.dao.php');


	$proveedor = ProveedorDAO::getByPK($_REQUEST["id"]);
	
	
	$foo = new CompraProveedor(  );
	$foo->setIdProveedor( $_REQUEST["id"] );
	
	$compras = CompraProveedorDAO::search($foo, "fecha", "desc");
	
	
	
	
?>
<script>	jQuery("#MAIN_TITLE").html( "<?php echo $proveedor->getNombre(); ?>");	</script>

<h2>Detalles del proveedor</h2>
<table border="0" cellspacing="1" cellpadding="1">
	<tr><td>Nombre</td>				<td><?php echo $proveedor->getNombre(); ?></td></tr>
	<tr><td>Direccion</td>			<td><?php echo $proveedor->getDireccion(); ?></td></tr>
	<tr><td>RFC</td>				<td><?php echo $proveedor->getRFC(); ?></td></tr>
	<tr><td>Telefono</td>			<td><?php echo $proveedor->getTelefono(); ?></td></tr>
	<tr><td>E Mail</td>				<td><?php echo $proveedor->getEMail(); ?></td></tr>		
	<tr><td>Tipo de proveedor</td>	<td><?php echo $proveedor->getTipoProveedor(); ?></td></tr>
	<tr><td colspan=3><input id="submit" type="button" onClick="editar()" value="Editar proveedor"/></td></tr>
</table>


<h2>Compras a este proveedor</h2>
<?php

	$header = array(
			"folio" => "Remision",
			"fecha" => "Fecha llegada",
			"arpillas" => "Arpillas en viaje",
			"peso_recibido" => "Peso recibido",
			"peso_por_arpilla" => "Peso por arpilla"
		);

	$tabla = new Tabla($header, $compras);
	$tabla->addColRender("fecha", "toDate");
	$tabla->render();

?>
