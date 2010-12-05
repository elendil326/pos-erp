<?php



/*
 * Lista de Clientes
 */ 


require_once("../../server/model/cliente.dao.php");

$q = new Cliente();
$q->setActivo(1);

$clientes = ClienteDAO::search($q);


echo "<table border='1' >";

foreach( $clientes as $cliente )
{
	echo "<tr>";
	echo "<td>" . $cliente->getIdCliente() . "</td>" ;
	echo "<td>" . $cliente->getNombre() . "</td>" ;
	echo "<td>" . $cliente->getDireccion() . "</td>" ;
	echo "<td>" . $cliente->getRfc() . "</td>" ;
	echo "<td>" . $cliente->getTelefono() . "</td>" ;
	echo "</tr>";
}


echo "</table>";