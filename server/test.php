<?php

//incluir la capa de abstraccion de base de datos
require_once("db/adodb5/adodb.inc.php");

//incluir el modelo vo y ado
require_once("model/model.inc.php");


//conectar a la base de datos
$db = ADONewConnection( "mysql" ); 
$db->debug = true;
$db->Connect("localhost", "root", "", "pos");




//crear cliente
$cliente = new Cliente();

//set client details
$cliente->setRFC("DIL763276GT");
$cliente->setNombre("Dilba Monica");
$cliente->setDireccion("asdffsdaf adsf asd");
$cliente->setTelefono("38060");
$cliente->setEMail("asdl@asdfjk.net");
$cliente->setLimiteCredito("20000");
$cliente->setDescuento("30");

//guardar este nuevo cliente
ClienteDAO::save( $cliente );

//modificar cliente
$cliente->setNombre("Alan Gonzalez");
ClienteDAO::save( $cliente );

//obtener datos de cliente
$id = $cliente->getIdCliente();

//obtener un cliente por llave primaria
$otro_cliente = ClienteDAO::getByPK( $id );

//obtener todos los clientes
$clientes = ClienteDAO::getAll();

//eso regresa un arreglo de clientes
$nombre_de_primer_cliente = $clientes[ 0 ]->getNombre();

//eliminar al primer cliente
$cliente_x = $clientes[0];

ClienteDAO::delete( $cliente_x );

//despues de eliminado este cliente deber ser null
var_dump($cliente_x);

//buscar todos los clientes que tengan limite de credito igual a 20000
$cliente = new Cliente();
$cliente->setLimiteCredito("20000");
$resultados = ClienteDAO::search($cliente);


foreach($resultados as $c ){
	echo $c->getNombre() . "<br>";
}


//buscar todos los clientes que tengan limite de credito igual a 20000 y se llamen alan
$cliente = new Cliente();
$cliente->setLimiteCredito("20000");
$cliente->setNombre("alan");
$resultados = ClienteDAO::search($cliente);


foreach($resultados as $c ){
	echo $c->getNombre() . "<br>";
}