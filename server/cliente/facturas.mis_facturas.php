<?php

	require_once("model/cliente.dao.php");
	require_once("model/factura_venta.dao.php");
	require_once("model/factura_compra.dao.php");	
	require_once("model/ventas.dao.php");	
	
	if(!function_exists("renderSucursal")){
		function renderSucursal($id_sucursal){
			$s = SucursalDAO::getByPK( $id_sucursal );
			if(!$s){
				return "!";
			}

			return $s->getDescripcion();

		}		
	}

	
	if(!function_exists("upperCase")){
		function upperCase($s){
			return strtoupper($s);
		}	
	}
	
?><h2>Mis facturas</h2>

<?php

	$facturas = FacturaVentaDAO::obtenerVentasFacturadasDeCliente($_SESSION["cliente_id"]);
	
	$header = array(  
			"id_venta" => "Venta", 
			"fecha" => "Fecha",
			"id_sucursal" => "Sucursal",
			"tipo_venta" => "Tipo de venta",
			"total" => "Total" );

	$tabla = new Tabla( $header, $facturas );
	$tabla->addOnClick("id_venta", "(function(id){window.location = 'compras.php?action=detalle&id='+id;})");
	$tabla->addNoData("Usted no ha solicitado ninunga factura.");
	$tabla->addColRender( "fecha", "toDate" ); 
	$tabla->addColRender( "total", "moneyFormat" ); 
	$tabla->addColRender( "tipo_venta", "upperCase" );	 
	$tabla->addColRender( "id_sucursal", "renderSucursal" ); 
	$tabla->render();
?>