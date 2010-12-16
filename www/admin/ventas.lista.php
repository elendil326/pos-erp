<h1>Ventas</h1>


<script>


    function mostrarDetallesVenta (vid){
        window.location = "ventas.php?action=detalles&id=" + vid;
    }
</script>
<?php

/*
 * Lista de Clientes
 */ 


require_once("controller/ventas.controller.php");
require_once('model/cliente.dao.php');
require_once('model/sucursal.dao.php');

?><h2>Ultimas ventas</h2> <?php
//obtener los clientes del controller de clientes

$ventas = VentasDAO::getAll (1, 50, 'fecha', 'desc');

//render the table
$header = array(
	"id_venta"=>  "Venta",
	"id_sucursal"=>  "Sucursal",
	"id_cliente"=>  "Cliente",
	"tipo_venta"=>  "Tipo",
	"fecha"=>  "Fecha",
	"subtotal"=>  "Subtotal",
	//"iva"=>  "IVA",
	"descuento"=>  "Descuento",
	"total"=>  "Total",

	//"pagado"=>  "Pagado" 
    );




function getNombrecliente($id)
{
    if($id < 0){
         return "Caja Comun";
    }
    return ClienteDAO::getByPK( $id )->getNombre();
}




function getDescSuc($sid)
{

    return SucursalDAO::getByPK( $sid )->getDescripcion();

}

function setTipocolor($tipo)
{
        if($tipo =="credito")
            return "<b>Credito</b>";
        return "Contado";
}




$tabla = new Tabla( $header, $ventas );
$tabla->addColRender( "subtotal", "moneyFormat" ); 
$tabla->addColRender( "saldo", "moneyFormat" ); 
$tabla->addColRender( "total", "moneyFormat" ); 
$tabla->addColRender( "pagado", "moneyFormat" ); 
$tabla->addColRender( "tipo_venta", "setTipoColor" ); 
$tabla->addColRender( "id_cliente", "getNombreCliente" ); 
$tabla->addColRender( "id_sucursal", "getDescSuc" ); 
$tabla->addOnClick("id_venta", "mostrarDetallesVenta");
$tabla->addColRender( "descuento", "percentFormat" );
$tabla->render();
