<?php

require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");
require_once('model/actualizacion_de_precio.dao.php');

function toUnit( $e, $row )
{
	if($e == "NA"){
		return  "";
	}
	

		
	if( $row["medida"] == "kilogramo" ){
		
		if(isset($row['peso_por_arpilla']))
			return "<b>" . number_format($e/$row['peso_por_arpilla'], 2) . "</b>Arp  " . "(<b>" . number_format($e, 2) . "</b>".smallUnit($row["medida"]).") ";			
		else
			return "<b>" . number_format($e/60, 2) . "</b>Arp  " . "(<b>" . number_format($e, 2) . "</b>".smallUnit($row["medida"]).") ";			


	}
	
	return "<b>" . number_format($e, 2) . "</b>" . smallUnit($row["medida"]) ;
}


function toUnitProc($e, $row){
	if($e == "NA"){
		return  "";
	}


	return "<b>" . number_format($e/60, 2) . "</b>Arp  " . "(<b>" . number_format($e, 2) . "</b> ".smallUnit($row["medida"]).") ";

	
}


function smallUnit($unit){
	switch( $unit ){
		case "kilogramo": return "Kgs";
		case "pieza": return "Pzas";
	}
}

?> <script>
	jQuery("#MAIN_TITLE").html("Inventario de sucursales");
</script> <?php



    //get sucursales
    $sucursales = listarSucursales();

    foreach( $sucursales as $sucursal ){
	
	    print ("<h2>" . $sucursal["descripcion"] . "</h2>");
	
		//obtener los clientes del controller de clientes
		$inventario = listarInventario( $sucursal["id_sucursal"] );

		//render the table
		$header = array( 
			"productoID" 		=> "ID",
			"descripcion"		=> "Descripcion",
			"precioVenta"		=> "Precio a la venta",
			"existenciasOriginales"		=> "Originales",			
			"existenciasProcesadas"		=> "Procesadas" );

		$tabla = new Tabla( $header, $inventario );
		$tabla->addColRender( "precioVenta", "moneyFormat" ); 
		$tabla->addColRender( "existenciasOriginales", "toUnit" ); 
		$tabla->addColRender( "existenciasProcesadas", "toUnitProc" ); 		
	    $tabla->addNoData("Esta sucursal no tiene nigun registro de productos en su inventario");
		$tabla->render();
    }



?>
<script type="text/javascript" charset="utf-8">
	function detalles( a ){
		window.location = "inventario.php?action=detalle&id=" + a;
	}
</script>
