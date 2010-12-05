<?php

require_once("../server/model/ventas.dao.php");
require_once("../server/model/factura_venta.dao.php");

function insertarFactura( $args ){

    if( !isset( $args['id_venta'] ) )
    {
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    if( !( $venta = VentasDAOBase::getByPK( $args['id_venta'] ) ) )
    {
        die( '{"success": false, "reason": "No se tiene registro de esa venta." }' );
    }

    if( $venta->getTotal() != $venta->getPagado() )
    {
        die( '{"success": false, "reason": "No se ha liquidado esa venta." }' );
    }

    $factura_venta = new FacturaVenta();
    $factura_venta->setIdVenta( $args['id_venta'] );

    try
    {
        if( FacturaVentaDAO::save( $factura_venta) > 0 )
        {
            printf( '{ "succes" : true }' );
        }
        else
        {
             die( '{"success": false, "reason": "No se pudo guardar la factura." }' );
        }
    }
    catch (Exception $e)
    {
        die( '{ "succes" : "false" , "reason" : "Error al intentar guardar la factura."}' );
    }

}

function eliminarFactura( $args ){

    if( !isset( $args['folio'] ) )
    {
        die('{ "success" : "false" , "reason" : "Faltan datos" }' );
    }

    try
    {
        $factura = FacturaVentaDAO::getByPK( $args['folio'] );

        if( is_null( $factura ) )
        {
            die( '{ "succes" : "false" , "reason" : "La factura que desea eliminar no existe."}' );
        }
        
        if( FacturaVentaDAO::delete( $factura ) > 0)
        {
            printf( '{ "succes" : true }' );
        }
        else
        {
            die( '{ "succes" : "false" , "reason" : "No se pudo eliminar la factura."}' );
        }
    }
    catch (Exception $e)
    {
        die( '{ "succes" : "false" , "reason" : "Error al intentar borrar la factura."}' );
    }
}

switch( $args['action'] )
{
    case '800':
        insertarFactura( $args );
    break;

    case '801':
        eliminarFactura( $args );
    break;
}

?>