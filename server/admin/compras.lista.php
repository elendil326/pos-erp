<?php

require_once('model/cliente.dao.php');
require_once('model/sucursal.dao.php');
require_once('model/compra_cliente.dao.php');

?>


<script>

    function mostrarDetallesCompra (cid){
        window.location = "compras.php?action=detalleCompraCliente&id=" + cid;
    }

</script>

		<?php
	    $sucursales = SucursalDAO::getAll();

	    ?>





<h2>Ultimas compras</h2> <?php
//obtener los clientes del controller de clientes

$ventas = CompraClienteDAO::getAll (1, 50, 'fecha', 'desc');

//render the table
$header = array(
	"id_compra"=>  "Compra",
	"id_sucursal"=>  "Sucursal",
	"id_cliente"=>  "Cliente",
	"tipo_compra"=>  "Tipo",
	"fecha"=>  "Fecha",
	//"subtotal"=>  "Subtotal",
	//"iva"=>  "IVA",
	//"descuento"=>  "Descuento",
	"total"=>  "Total",

	//"pagado"=>  "Pagado" 
    );




function getNombrecliente($id)
{
    if($id < 0){
         return "Caja Comun";
    }
    return ClienteDAO::getByPK( $id )->getRazonSocial();
}




function getDescSuc($sid)
{
	$suc = SucursalDAO::getByPK( $sid );
	
	if($suc)
		return $suc->getDescripcion();
	else 
		return "Error";

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
$tabla->addColRender( "fecha", "toDate" ); 
$tabla->addColRender( "tipo_compra", "setTipoColor" ); 
$tabla->addColRender( "id_cliente", "getNombreCliente" ); 
$tabla->addColRender( "id_sucursal", "getDescSuc" ); 
$tabla->addOnClick("id_compra", "mostrarDetallesCompra");
$tabla->addColRender( "descuento", "percentFormat" );
$tabla->render();
