<?php


	require_once('model/compra_proveedor.dao.php');
	require_once('model/compra_proveedor_flete.dao.php');	
	require_once('model/detalle_compra_proveedor.dao.php');	
	require_once('model/proveedor.dao.php');
?>

<script>
	jQuery("#MAIN_TITLE").html("Compras a proveedores");
	function d(id){
		window.location = "proveedor.php?action=detalleEmbarque&cid=" + id;
	}
</script>

<h2>Embarques de proveedores</h2>
<?php

	function prov($pid){
		$foo = ProveedorDAO::getByPK( $pid );
		return $foo->getNombre();
	}

	$c = CompraProveedorDAO::getAll(1, 50, "fecha", "desc");
	$header = array(
			"id_compra_proveedor" => "ID Compra",
			"folio" => "Remision",
			"id_proveedor" => "Proveedor",
			"fecha" => "Fecha de llegada",
			"arpillas" => "Total de arpillas",
			"peso_recibido" => "Peso recibido"
		);
		
	$tabla = new Tabla($header, $c);
	$tabla->addColRender("fecha", "toDate");
	$tabla->addColRender("id_proveedor", "prov");	
	$tabla->addOnClick( "id_compra_proveedor", "d" );
	$tabla->render();

?>
