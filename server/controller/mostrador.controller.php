<?php

/**
 *  Controller para mostrador
 */
 
require_once("../server/model/ventas.dao.php");
require_once("../server/model/cliente.dao.php");
require_once("../server/model/detalle_venta.dao.php");

function vender( $args ){

    /*
    data={"cliente": {"clienteComun": true|false, "clienteId": n, "venta": {"productos": [{"id_producto":n, "cantidad":n, "precio":n }], "tipo": "credito|contado" } } }
    */

    if(!isset($args['data']))
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    try
    {
        $data = json_decode( $args['data'] );
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario( $_SESSION['userid'] );
    $venta->setTotal( 0 );
    $venta->setIdSucursal( $_SESSION['sucursal'] );
    $venta->setIp( $_SERVER['REMOTE_ADDR']  );
    $venta->setPagado( 0 );

    //verificar si la venta es a la caja comun o a un cliente corriente
    if($data->cliente->clienteComun)
    {

        //entra si es caja comun

        $venta->setIdCliente( $_SESSION['sucursal'] * -1 );
        $venta->setTipoVenta( "contado" );
        $venta->setDescuento( 0 );

        try{
            if (VentasDAO::save($venta)) 
            {
                $id_venta =  $venta->getIdVenta();
            } 
            else 
            {
                die( '{"success": false, "reason": "No se pudo registrar la venta" }' );
            }
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "' . $e . '" }' );
        }

    }
    else
    {

        //entra si es un cliente corriente

        $venta->setIdCliente( $data->cliente->clienteId );
        $venta->setTipoVenta( $data->cliente->venta->tipo );

        try{
            if ( $cliente = ClienteDAOBase::getByPK( $data->cliente->clienteId ) ) 
            {
                $descuento = $cliente->getDescuento();
            } 
            else 
            {
                die( '{"success": false, "reason": "No se tienen registros sobre el cliente ' . $data->cliente->clienteId . '" }' );
            }
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "' . $e . '" }' );
        }

        $venta->setDescuento( $descuento );

        try{
            if (VentasDAO::save($venta)) 
            {
                $id_venta =  $venta->getIdVenta();
            } 
            else 
            {
                die( '{"success": false, "reason": "No se pudo registrar la venta" }' );
            }
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "' . $e . '" }' );
        }
    }

    //hasta aqui ya esta creada la venta, ahora sigue llenar el detalle de la venta
    $productos = $data->cliente->venta->productos;

    //total de la venta
    $subtotal = 0;

    //TODO:Esto estaria muy bien si estuviera con una transaccion y si no se pudiera guardar algun producto en el detalle de la venta, se disparara un rollover

    foreach($productos as $producto)
    {
        $detalle_venta = new DetalleVenta();
        $detalle_venta->setIdVenta( $id_venta );
        $detalle_venta->setIdProducto( $producto->id_producto );
        $detalle_venta->setCantidad( $producto->cantidad );
        $detalle_venta->setPrecio( $producto->precio );

        try{
            if (DetalleVentaDAO::save($detalle_venta)) 
            {
                $subtotal += ( $detalle_venta->getCantidad() * $detalle_venta->getPrecio() );
            } 
            else 
            {
                die( '{"success": false, "reason": "No se pudo registrar la venta" }' );
            }
        }
        catch(Exception $e)
        {
            die( '{"success": false, "reason": "' . $e . '" }' );
        }

    }

    //ya que se tiene el total de la venta se actualiza el total de la venta
    $venta->setSubtotal( $subtotal );
    $total = ( $subtotal - ( ($subtotal * $descuento) / 100 ) );
    $venta->setTotal( $total );

    //si la venta es de contado, hay que liquidarla
    if($venta->getTipoVenta() == "contado")
    {
        $venta->setPagado( $total );
    }

    try
    {
        if ( VentasDAO::save($venta) )
        {
            printf('{"success": true}');
        }
        else 
        {
            die( '{"success": false, "reason": "No se pudo actualizar el total de la venta" }' );
        }
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "' . $e . '" }' );
    }

}

switch( $args['action'] ){

    case 100://realzar una venta
        vender($args);
    break;

}

?>
