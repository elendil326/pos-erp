<?php


	
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

	
?><h2>Mis compras</h2>

<?php

	$v_query = new Ventas();
	$v_query->setIdCliente( $_SESSION["cliente_id"] );
	
	$ventas = VentasDAO::search($v_query);
	
	$header = array(  
			"id_venta" => "Venta", 
			"fecha" => "Fecha",
			"id_sucursal" => "Sucursal",
			"tipo_venta" => "Tipo de venta",
			"total" => "Total" );

	$tabla = new Tabla( $header, $ventas );
	$tabla->addOnClick("id_venta", "(function(id){window.location = 'compras.php?action=detalle&id='+id;})");
	$tabla->addNoData("Usted no ha realizado ninguna compra.");
	$tabla->addColRender( "fecha", "toDate" ); 
	$tabla->addColRender( "total", "moneyFormat" ); 
	$tabla->addColRender( "tipo_venta", "upperCase" );	 
	$tabla->addColRender( "id_sucursal", "renderSucursal" ); 
	$tabla->render();
	
?>