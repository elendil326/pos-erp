<?php

require_once('model/proveedor.dao.php');
require_once('model/inventario.dao.php');
require_once('model/compra_sucursal.dao.php');
require_once('model/detalle_compra_sucursal.dao.php');
require_once('model/inventario_maestro.dao.php');

require_once("controller/inventario.controller.php");


?>

<script>
	function detalleCompraSucursal(id){
		window.location = "inventario.php?action=detalleCompraSucursal&cid=" + id;
	}
</script>
<h2>Compras de sucursal a centro de administracion</h2>
<?php

	$comprasSucursal = CompraSucursalDAO::getAll(  );
	
	$proveedores = array();
	$centro = array();
	
	foreach($comprasSucursal as $compra){
		if($compra->getIdProveedor() === null){
			array_push( $centro, $compra );
		}else{
			array_push( $proveedores, $compra );			
		}
	}
	
	
	$header = array(
			"id_compra" => "ID Compra",
		    "fecha"=> "Fecha",
		    "total"=> "Total",
		    "pagado"=> "Pagado" );
		
	$tabla = new Tabla($header, $centro);
	$tabla->addColRender("fecha", "toDate");
	$tabla->addColRender("total", "moneyFormat");
	$tabla->addColRender("pagado", "moneyFormat");
	$tabla->addOnClick("id_compra", "detalleCompraSucursal" );
	$tabla->render();


?>




<h2>Compras de sucursal a otros proveedores</h2>
<?php


$tabla = new Tabla($header, $proveedores);
$tabla->addNoData("No hay compras a proveedores por parte de las sucursales");
$tabla->addColRender("pagado", "moneyFormat");
$tabla->addColRender("total", "moneyFormat");
$tabla->render();

?>