
<h2>Clientes activos</h2><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/clientes.controller.php");


//obtener los clientes del controller de clientes
$clientes = listarClientes();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "ciudad" => "Ciudad"  );
$tabla = new Tabla( $header, $clientes );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->render();

?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>
