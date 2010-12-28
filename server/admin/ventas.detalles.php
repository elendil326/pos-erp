<h1>Detalles de la venta</h1><?php


require_once("controller/ventas.controller.php");
require_once("controller/clientes.controller.php");
require_once("model/cliente.dao.php");
require_once("model/sucursal.dao.php");
require_once("model/usuario.dao.php");

$detalles = detalleVenta($_REQUEST['id']);
$venta = $detalles['detalles'];

?><h2>Detalles</h2>


<table>
    <tr>
        <td><b>ID Venta</b></td>
        <td><?php echo $venta->getIdVenta(); ?></td>
    </tr>

    <tr>
        <td><b>Cliente</b></td>
        <td><?php
            if($venta->getIdCliente() < 0){
                echo "Caja Comun";
            }else{
                echo ClienteDAO::getByPK( $venta->getIdCliente() )->getNombre();
            }

        ?></td>
    </tr>

    <tr>
        <td><b>Tipo Venta</b></td>
        <td><?php echo $venta->getTipoVenta(); ?></td>
    </tr>

    <tr>
        <td><b>Fecha</b></td>
        <td><?php echo $venta->getFecha(); ?></td>
    </tr>





    <tr>
        <td><b>Sucursal</b></td>
        <td><?php 
            echo SucursalDAO::getByPK( $venta->getIdSucursal() )->getDescripcion();
        ?></td>
    </tr>

    <tr>
        <td><b>Cajero</b></td>
        <td><?php 
            echo UsuarioDAO::getByPK( $venta->getIdUsuario() )->getNombre();
        ?></td>
    </tr>

    <tr>
        <td><b>Subtotal</b></td>
        <td><?php echo moneyFormat($venta->getSubtotal()); ?></td>
    </tr>

    <tr>
        <td><b>Descuento</b></td>
        <td><?php echo percentFormat($venta->getDescuento()); ?></td>
    </tr>

    <tr>
        <td><b>Total</b></td>
        <td><?php echo moneyFormat($venta->getTotal()); ?></td>
    </tr>

    <tr>
        <td><b>Pagado</b></td>
        <td><?php echo moneyFormat($venta->getPagado()); ?></td>
    </tr>

    <?php if($venta->getTipoVenta() == 'credito'){ ?>
    <tr>
        <td><b>Saldo</b></td>
        <td><b><?php echo moneyFormat($venta->getPagado()-$venta->getTotal()); ?></b></td>
    </tr>
    <?php } ?>

</table>


<h2>Articulos en la venta</h2><?php


//render the table
$header = array( "id_producto" => "ID", "descripcion" => "Descripcion", "cantidad" => "Cantidad", "precio" => "Precio" );

$tabla = new Tabla( $header, $detalles['items'] );
$tabla->addColRender( 'precio', "moneyFormat" );
$tabla->render();








if($venta->getTipoVenta() == 'credito'){
    ?><h2>Abonos a esta venta</h2><?php

    $abonos = listarAbonos($venta->getIdCliente(), $venta->getIdVenta() );

    $header = array( 
	    "id_pago" => "Pago", 
	    "id_venta" => "Venta", 
	    "sucursal" => "Sucursal",
	    "cajero" => "Cajero",
	    "fecha" => "Fecha",
	    "monto" => "Monto" );

    $tabla = new Tabla( $header, $abonos );
    $tabla->addColRender( 'precio', "moneyFormat" );
    $tabla->addColRender( 'monto', "moneyFormat" );
    $tabla->render();
}

?>
