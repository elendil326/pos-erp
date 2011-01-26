<?php

require_once("model/ventas.dao.php");
require_once("model/inventario.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/factura_venta.dao.php");
require_once('logger.php');


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



function listarVentas( $sid = null ){
	
	if(!$sid){
	    Logger::log('listarVentas : No esta definida $_SESSION[sucursal], se regresaran todas las ventas de todas las sucursales');
		return VentasDAO::getAll();		
	}else{
		$v = new Ventas();
		$v->setIdSucursal($sid);
		Logger::log("listarVentas : Listando ventas de la sucursal {$_SESSION[sucursal]}");
		return VentasDAO::search($v);
	}

	
}




function detalleVenta( $vid ){


    $venta = VentasDAO::getByPK( $vid );



    $q = new DetalleVenta();
    $q->setIdVenta( $vid ); 
    
    $detallesVenta = DetalleVentaDAO::search( $q );
    
    $items = array();
    
    foreach( $detallesVenta as $dV )
    {
    
        $productoData = InventarioDAO::getByPK( $dV->getIdProducto() );
        
        array_push( $items , array(
            "id_producto" => $dV->getIdProducto(),
            "descripcion" => $productoData->getDescripcion(),
            "cantidad" => $dV->getCantidad(),
            "precio" => $dV->getPrecio()
        ));
    }

    $results = array(
            'detalles' => $venta,
            'items' => $items
        );

    return $results;
    


}









if(isset($args['action'])){
	switch( $args['action'] )
	{
	    case '800':
	        insertarFactura( $args );
	    break;

	    case '801':
	        eliminarFactura( $args );
	    break;
	    
	    case '802':
	        printf( '{ "succes" : true, "datos": [%s] }',  json_encode( listarVentas( $_SESSION['sucursal'] ) ));
	    break;
	    
	}	
}


?>
