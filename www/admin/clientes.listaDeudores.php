<h2>Clientes deudores</h2><?php

/*
 * Lista de Clientes deudores
 */ 


require_once("controller/clientes.controller.php");


//obtener los clientes deudores del controller de clientes
$clientes = listarClientesDeudores();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "saldo" => "Saldo" );

$tabla = new Tabla( $header, $clientes );
$tabla->addColRender( array('saldo' => function($a){return sprintf( "<span style='color:red'>$%.2f</span>", $a);})  );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->render();


?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>
