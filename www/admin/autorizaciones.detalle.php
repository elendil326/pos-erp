<h1>Detalles de autorizacion</h1><?php


require_once('model/autorizacion.dao.php');


$autorizacion = AutorizacionDAO::getByPK( $_REQUEST['id'] );

$autorizacionDetalles = json_decode( $autorizacion->getParametros() );

?>
<h2>Detalles de la autorizacion</h2>

<table border="0" cellspacing="5" cellpadding="5">
	<tr><td><b>ID Autorizacion</b></td><td><?php echo $autorizacion->getIdAutorizacion(); ?></td><td rowspan=12><div id="map_canvas"></div></td></tr>
	<tr><td><b>Usuario</b></td><td><?php echo $autorizacion->getIdUsuario(); ?></td></tr>
	<tr><td><b>Sucursal</b></td><td><?php echo $autorizacion->getIdSucursal(); ?></td></tr>
	<tr><td><b>Fecha de peticion</b></td><td><?php echo $autorizacion->getFechaPeticion(); ?></td></tr>
	<tr><td><b>Descripcion</b></td><td><?php echo $autorizacionDetalles->descripcion; ?></td></tr>	

</table>


<?php
switch( $autorizacionDetalles->clave ){

    case "201": 
        //solicitud de autorizcion de gasto
        echo $autorizacionDetalles->concepto;
        echo $autorizacionDetalles->monto;
    break;

    case "202": 
        //cambio de limite de credito
        echo $autorizacionDetalles->id_cliente;
        echo $autorizacionDetalles->cantidad;
    break;

    case "203": 
        //devoluciones
        echo $autorizacionDetalles->id_venta;
        echo $autorizacionDetalles->id_producto;
        echo $autorizacionDetalles->cantidad;
    break;

    case "204": 
        //cambio de precio
        echo $autorizacionDetalles->id_producto;
        echo $autorizacionDetalles->precio;

    break;

    case "205": 
        //merma
        echo $autorizacionDetalles->id_compra;
        echo $autorizacionDetalles->id_producto;
        echo $autorizacionDetalles->cantidad;
    break;

    case "209": 
        //solicitud de surtir

        foreach ($autorizacionDetalles->productos as $producto)
        {
            echo $producto->id_producto;
            echo $producto->cantidad;            
        }
        
    break;


    default: 
}
?>
