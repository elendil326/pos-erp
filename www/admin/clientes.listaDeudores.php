<h2>Clientes deudores</h2><?php

/*
 * Lista de Clientes deudores
 */ 

require_once("model/cliente.dao.php");
require_once("controller/clientes.controller.php");


//obtener los clientes deudores del controller de clientes
$clientes = listarClientesDeudores();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion" );
$tabla = new Tabla( $header, $clientes );
$tabla->render();
