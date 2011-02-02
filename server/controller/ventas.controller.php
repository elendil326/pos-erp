<?php

require_once("model/ventas.dao.php");
require_once("model/inventario.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/detalle_inventario.dao.php");
require_once("model/factura_venta.dao.php");
require_once("model/cliente.dao.php");
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


/**
* Regresa el detalle de la ultima venta.
*
*/

function listarUltimaVentaSucursal( $id_sucursal ){

    Logger::log('Listando ultima venta');

    //obtenemos un objeto con todas las ventas de la sucursal actual
    $ventas = new Ventas();
    $ventas -> setIdSucursal( $id_sucursal );
    $ventas -> setCancelada( "0" );
    $ventas = VentasDAO::search( $ventas, 'id_venta', 'desc' );
    
    //verificamos que haya mas ventas
    if( count( $ventas ) <= 0 ){
        Logger::log('No hay ninguna venta registrada.');
        die ( '"success" : false, "reason": "No se ha registrado ninguna venta"');
    }
    
    $id_ultima_venta =  $ventas[0]  -> getIdVenta();
    
    //obtenemos el detalle de la ultima venta
    $detalle_venta = new DetalleVenta();
    $detalle_venta -> setIdVenta( $id_ultima_venta  );
    $detalle_venta = DetalleVentaDAO::search( $detalle_venta );
    
    $array_detalle_venta = array();
    
    
    
    foreach( $detalle_venta as $producto ){
        
        $productoData = InventarioDAO::getByPK( $producto->getIdProducto() );	
        
        array_push(
            $array_detalle_venta, array(
                "id_producto" => $producto -> getIdProducto(),
                "descripcion" => $productoData -> getDescripcion(),
                "cantidad" => $producto -> getCantidad(),
                "cantidad_procesada" => $producto -> getCantidadProcesada(),
                "precio" => $producto -> getPrecio(),
                "precio_procesada" => $producto -> getPrecioProcesada(),
            )
        );
        
    }
    
    $cliente = ClienteDAO::getByPK( $ventas[0]  -> getIdCliente() );
    
    $detalle = new stdClass();
    $detalle -> id_venta =  $id_ultima_venta;
    $detalle -> detalle_venta = $array_detalle_venta;
    $detalle -> cliente = $cliente;
    
    return $detalle;

}



/**
* elimina la venta indicada
*/

function cancelarVenta( $args ){

    Logger::log( "Iniciando proceso de cancelacion de venta." );
    
    if( !isset( $args['id_venta'] ) )
    {
        Logger::log( "No se especifico la venta que desea eliminar." );
        die( '{ "success" : false , "reason" : "No se especifico la venta que desea eliminar."}' );
    }

    //verificamos qeu la venta exista
    try{
			
			if( !$venta = VentasDAO::getByPK( $args['id_venta'] ) )
			{
			    Logger::log( "No se encontro registro de la venta a eliminar" );
                die( '{ "success" : false , "reason" : "No se encontro registro de la venta ' . $args['id_venta'] . '"}' );
			}
			
			if(  $venta -> getCancelada() == "1" )
			{
			    Logger::log( "La venta ya estaba cancelada" );
                die( '{ "success" : false , "reason" : "La venta ' . $args['id_venta'] . ' ya estaba cancelada."}' );
			}
			
			//almacenamos el detalle de la venta para cargarlo nuevamente al inventario
			$detalle_venta = new DetalleVenta();
			$detalle_venta -> setIdVenta( $venta -> getIdVenta() );
			$detalle = DetalleVentaDAO::search( $detalle_venta  );
			
			DAO::transBegin();	
			
			//iteramos el detalle de la venta
			foreach( $detalle as $venta_producto )
			{
			    //por cada producto en el detalle de la venta se lo cargaremos nuevamente al detalle del inventario de la sucursal			    
			    if( !$detalle_inventario = DetalleInventarioDAO::getByPK( $venta_producto -> getIdProducto(), $_SESSION['sucursal'] ) )
			    {
			        Logger::log( "No se encontro registro del producto vendido en el inventario de la sucursal." );
                    DAO::transRollback();
                    die( '{ "success" : false , "reason" : "No se encontro registro del producto vendido en el inventario de la sucursal."}' );
			    }
			    
			    $detalle_inventario -> setExistencias(  $detalle_inventario -> getExistencias() + $venta_producto -> getCantidad() ); 
			    $detalle_inventario -> setExistenciasProcesadas( $detalle_inventario -> getExistenciasProcesadas() + $venta_producto -> getCantidadProcesada() );
			    
			    DetalleInventarioDAO::save( $detalle_inventario );
			    
			}			
		    
		    $venta -> setCancelada( 1 );
		    VentasDAO::save( $venta );	
            DAO::transEnd();
            Logger::log( "Venta cancelada con exito." );
            printf( '{ "success" : true }' );
            
			
    }catch(Exception $e){
        Logger::log( $e);
        DAO::transRollback();
        die( '{ "success" : false , "reason" : "No se encontro registro de la venta ' . $args['id_venta'] . '."}' );
	}

}


if(isset($args['action'])){
	switch( $args['action'] )
	{
	    case 800:
	        insertarFactura( $args );
	    break;

	    case 801:
	        eliminarFactura( $args );
	    break;
	    
	    case 802:
	        printf( '{ "succes" : true, "datos": [%s] }',  json_encode( listarVentas( $_SESSION['sucursal'] ) ));
	    break;
	    
	    case 803:
	        
	        $detalle_venta = listarUltimaVentaSucursal($_SESSION['sucursal'] );
	    
	         printf( '{ "success" : true, "detalle_venta": %s, "id_venta": %s, "cliente": %s }',  json_encode( $detalle_venta -> detalle_venta ), $detalle_venta -> id_venta, $detalle_venta -> cliente );
	    break;
	    
	    case 804:
             cancelarVenta( $args );
	    break;
	   
	    
	}	
}


?>
