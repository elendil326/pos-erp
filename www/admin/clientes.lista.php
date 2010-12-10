<h1>Clientes</h1><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/clientes.controller.php");


?><h2>Todos los clientes</h2> <?php
//obtener los clientes del controller de clientes
$clientes = listarClientes();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "ciudad" => "Ciudad"  );
$tabla = new Tabla( $header, $clientes );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->render();




?><h2>Clientes deudores</h2> <?php

//obtener los clientes deudores del controller de clientes
$clientes = listarClientesDeudores();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion", "saldo" => "Saldo" );

$tabla = new Tabla( $header, $clientes );
$tabla->addColRender( 'saldo', "moneyFormat" );
$tabla->addOnClick("id_cliente", "mostrarDetalles");
$tabla->render();


?>
<script type="text/javascript" charset="utf-8">
	function mostrarDetalles( a ){
		window.location = "clientes.php?action=detalles&id=" + a;
	}
</script>
