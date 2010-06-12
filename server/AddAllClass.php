<?php 	
	include_once("libBD.php");
	include_once("cliente.php");
	include_once("compra.php");
	include_once("cotizacion.php");
	include_once("cuenta_cliente.php");
	include_once("cuenta_proveedor.php");
	include_once("detalle_compra.php");
	include_once("detalle_cotizacion.php");
	include_once("detalle_inventario.php");
	include_once("detalle_venta.php");
	include_once("factura_compra.php");
	include_once("factura_venta.php");
	include_once("impuesto.php");
	include_once("inventario.php");
	include_once("nota_remision.php");
	include_once("pagos_compra.php");
	include_once("pagos_venta.php");
	include_once("productos_proveedor.php");
	include_once("proveedor.php");
	include_once("sucursal.php");
	include_once("usuario.php");
	include_once("venta.php"); 
	include_once("listar.php"); 
	
	function fail($razon){
		echo "{success : false, error: '".$razon."'}";
		return;
	}
	function ok(){
		echo "{success : true}";
		return;
	}
?>