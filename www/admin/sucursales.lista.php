
<h2>Lista de Sucursales</h2><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/sucursales.controller.php");


//obtener los clientes del controller de clientes
$sucursales = listarSucursales();

var_dump($sucursales);


//render the table
$header = array(  "id_sucursal" => "ID", "descripcion" => "Descripcion" );
$tabla = new Tabla( $header, $sucursales );
$tabla->render();

