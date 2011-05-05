<?php

require_once('model/proveedor.dao.php');

$data = ProveedorDAO::getAll();

$header = array( 
	//"id_proveedor" => "ID",
	"nombre"=> "Nombre",
	"direccion"=> "Direccion",
	//"rfc"=> "RFC",
	"telefono"=> "Telefono",
	/* "e_mail"=> "E Mail" */);
	
$tabla = new Tabla( $header, $data );
$tabla->addOnClick("id_proveedor", "mostrarDetallesProveedor");
$tabla->addNoData("No hay proveedores.");


?>


<h2>Lista de proveedores</h2>
<?php $tabla->render();	?>

<script type="text/javascript" charset="utf-8">
	function mostrarDetallesProveedor ( sid ){
		window.location = "proveedor.php?action=detalles&id=" + sid;
	}
</script>


<?php

require_once( "admin/proveedor.nuevo.php");

?>