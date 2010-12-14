<h1>Detalles de la venta</h1><?php


require_once("controller/ventas.controller.php");
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
        <td><?php echo $venta->getDescuento(); ?></td>
    </tr>

    <tr>
        <td><b>Total</b></td>
        <td><?php echo moneyFormat($venta->getTotal()); ?></td>
    </tr>

    <tr>
        <td><b>Pagado</b></td>
        <td><?php echo moneyFormat($venta->getPagado()); ?></td>
    </tr>

</table>


<h2>Articulos en la venta</h2><?php


//render the table
$header = array( "id_producto" => "ID", "descripcion" => "Descripcion", "cantidad" => "Cantidad", "precio" => "Precio" );

$tabla = new Tabla( $header, $detalles['items'] );
$tabla->addColRender( 'precio', "moneyFormat" );
$tabla->render();
?>
