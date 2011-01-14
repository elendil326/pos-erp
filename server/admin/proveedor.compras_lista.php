<?php


	require_once('model/compra_proveedor.dao.php');
	require_once('model/compra_proveedor_flete.dao.php');	
	require_once('model/detalle_compra_proveedor.dao.php');	
	require_once('model/proveedor.dao.php');
?>

<script>
	jQuery("#MAIN_TITLE").html("Compras a proveedores");
</script>

<h2>Embarques de proveedores</h2>
<?php

	$c = CompraProveedorDAO::getAll();
	$header = array(
			"id_compra_proveedor" => "ID Compra",
			"folio" => "Remision",
			"id_proveedor" => "Proveedor",
			"fecha" => "Fecha de llegada",
			"peso_recibido" => "Peso recibido"
		);
		
	$tabla = new Tabla($header, $c);
	$tabla->addColRender("fecha", "toDate");
	$tabla->render();

?>
