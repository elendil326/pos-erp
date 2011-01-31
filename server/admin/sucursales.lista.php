
<?php

require_once("controller/sucursales.controller.php");
require_once("controller/inventario.controller.php");

$sucursales = listarSucursales();

print ("<h1>Lista de sucursales</h1>");

print ("<h2>Sucursales Activas</h2>");

$data = array();

foreach( $sucursales as $sucursal ){
	
	
	
	//obtener los clientes del controller de clientes
	$detalles = detallesSucursal( $sucursal["id_sucursal"] );
	array_push($data, $detalles); 
}


//render the table
$header = array( 
	"id_sucursal" => "ID",
	"descripcion"=> "Descripcion",
	"direccion"=> "Direccion",
	"rfc"=> "RFC",
	"telefono"=> "Telefono",
	"letras_factura"=> "Facturas" );
$tabla = new Tabla( $header, $data );
$tabla->addOnClick("id_sucursal", "mostrarDetallesSucursal");
$tabla->addNoData("No hay sucursales.");
$tabla->render();	


?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetallesSucursal ( sid ){
		window.location = "sucursales.php?action=detalles&id=" + sid;
	}
</script>



<?php

	include( "admin/sucursales.abrir.php" );

?>