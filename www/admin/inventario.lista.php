<h1>Inventario por sucursal</h1><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");





?><h2>Productos disponibles</h2><?php

	//obtener los clientes del controller de clientes
	$inventario = listarInventarioMaestro( );

	//render the table
	$header = array( 
		"id_producto" => "ID",
		"descripcion"=> "Descripcion",
		"precio_intersucursal"=> "Precio Intersucursal",
		"costo"=> "Costo",
		"medida"=> "Medida");

	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precio_intersucursal", "moneyFormat" ); 
	$tabla->addColRender( "costo", "moneyFormat" ); 
    $tabla->addOnClick( "id_producto", "detalles");
	$tabla->render();






//get sucursales
$sucursales = listarSucursales();

foreach( $sucursales as $sucursal ){
	

	print ("<h2>" . $sucursal["descripcion"] . "</h2>");
	
	//obtener los clientes del controller de clientes
	$inventario = listarInventario( $sucursal["id_sucursal"] );

	//render the table
	$header = array( 
		"productoID" => "ID",
		"descripcion"=> "Descripcion",
		"precioVenta"=> "Precio Venta",
		"existenciasMinimas"=> "Minimas",
		"existencias"=> "Existencias",
		"medida"=> "Tipo",
		"precioIntersucursal"=> "Precio Intersucursal" );
		

	
	$tabla = new Tabla( $header, $inventario );
	$tabla->addColRender( "precioVenta", "moneyFormat" ); 
	$tabla->addColRender( "precioIntersucursal", "moneyFormat" ); 
	$tabla->addNoData( "<h3>Esta sucursal no tiene inventario.</h3>"); 
    $tabla->addOnClick( "productoID", "detalles");
	$tabla->render();
}



?>
<script type="text/javascript" charset="utf-8">
	function detalles( a ){
		window.location = "inventario.php?action=detalle&id=" + a;
	}
</script>
