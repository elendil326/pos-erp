
<h2>Clientes activos</h2><?php

/*
 * Lista de Clientes
 */ 

require_once("model/cliente.dao.php");
require_once("controller/clientes.controller.php");


//obtener los clientes del controller de clientes
$clientes = listarClientes();

//render the table
$header = array(  "nombre" => "Nombre", "rfc" => "RFC", "direccion" => "Direccion" );
$tabla = new Tabla( $header, $clientes );
$tabla->render();
