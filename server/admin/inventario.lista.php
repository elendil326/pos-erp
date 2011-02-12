<?php

require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");
require_once('model/actualizacion_de_precio.dao.php');

function toUnit( $e )
{
	return "<b>" . number_format($e, 2) . "</b>kg";
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
		$tabla->addColRender( "existenciasProcesadas", "toUnit" ); 		
	    $tabla->addNoData("Esta sucursal no tiene nigun registro de productos en su inventario");
		$tabla->render();
    }



?>
<script type="text/javascript" charset="utf-8">
	function detalles( a ){
		window.location = "inventario.php?action=detalle&id=" + a;
	}
</script>
