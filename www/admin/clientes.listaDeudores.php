
<h2>Clientes deudores</h2>

<?php
/*
 * Lista de Clientes deudores
 */ 


require_once("../../server/model/cliente.dao.php");

$q = new Cliente();
$q->setActivo("1");
$q->setIdCliente("0");

$q2 = new Cliente();
$q2->setIdCliente("10000");

$clientes = ClienteDAO::byRange($q, $q2);



//crar la tabla
$header = array( "ID", "RFC", "Nombre", "Direccion", "Ciudad", "Telefono", "Email", "a", "a", "a", "a", "a", "a" );
$tabla = new Tabla( $header, $clientes );
$tabla->render();
