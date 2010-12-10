<h1>Inventario por sucursal</h1><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/inventario.controller.php");
require_once("controller/sucursales.controller.php");


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
	$tabla->render();
}



?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>
