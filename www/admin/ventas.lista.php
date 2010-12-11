<h1>Ventas</h1><?php

/*
 * Lista de Clientes
 */ 


require_once("controller/ventas.controller.php");


?><h2>Ultimas ventas</h2> <?php
//obtener los clientes del controller de clientes
$ventas = listarVentas();


//render the table
$header = array(
	"id_venta"=>  "Venta",
	"id_cliente"=>  "Cliente",
	"tipo_venta"=>  "Tipo",
	"fecha"=>  "Fecha",
	"subtotal"=>  "Subtotal",
	"iva"=>  "IVA",
	"descuento"=>  "Descuento",
	"total"=>  "Total",
	"id_sucursal"=>  "Suc",
	"id_usuario"=>  "Usu",
	"pagado"=>  "Pagado" );
	
$tabla = new Tabla( $header, $ventas );
$tabla->render();