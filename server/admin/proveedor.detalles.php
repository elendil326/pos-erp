<?php

	require_once('model/proveedor.dao.php');
	require_once('model/compra_proveedor.dao.php');
	require_once('model/detalle_compra_proveedor.dao.php');
	require_once('model/compra_proveedor_flete.dao.php');


	$proveedor = ProveedorDAO::getByPK($_REQUEST["id"]);
	
	
	$foo = new CompraProveedor(  );
	$foo->setIdProveedor( $_REQUEST["id"] );
	
	$compras = CompraProveedorDAO::search($foo, "fecha", "desc");
	
	
	function renderTipoProv($tipo){
		
		switch($tipo){
			case "admin" : return "Este proveedor surte al centro de administracion.";
		}
		
	}
	
?>
<script>	jQuery("#MAIN_TITLE").html( "<?php echo $proveedor->getNombre(); ?>");	</script>
<script type="text/javascript"> 
    

    function mostrarDetallesVenta (vid){
        window.location = "ventas.php?action=detalles&id=" + vid;
    }

    function editarProveedor (){
        window.location = "proveedor.php?action=editar&id=<?php echo $_REQUEST['id']; ?>";
    }
    
    </script>
<h2>Detalles del proveedor</h2>
<table border="0" cellspacing="5" cellpadding="5">
	<tr><td>Nombre</td>				<td><?php echo $proveedor->getNombre(); ?></td></tr>
	<tr><td>Direccion</td>			<td><?php echo $proveedor->getDireccion(); ?></td></tr>
	<tr><td>RFC</td>				<td><?php echo $proveedor->getRFC(); ?></td></tr>
	<tr><td>Telefono</td>			<td><?php echo $proveedor->getTelefono(); ?></td></tr>
	<tr><td>E Mail</td>				<td><?php echo $proveedor->getEMail(); ?></td></tr>		
	<tr><td>Tipo de proveedor</td>	<td><?php echo renderTipoProv( $proveedor->getTipoProveedor() ); ?></td></tr>
	<tr><td colspan=3>
		<h4><input id="submit" type="button" onClick="editarProveedor()" value="Editar proveedor"/></h4>
	</td></tr>
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
	$tabla->addNoData("No se han hecho compras a este proveedor.");	
	$tabla->render();

?>
