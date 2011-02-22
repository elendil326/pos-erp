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
        Logger::log('Error al insertar factual, no se ha especificado el id de la venta');
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    if( !( $venta = VentasDAOBase::getByPK( $args['id_venta'] ) ) )
    {
        Logger::log('Error la insertar factura, no se tiene registro de la venta id_venta');
        die( '{"success": false, "reason": "No se tiene registro de esa venta." }' );
    }

    if( $venta->getTotal() != $venta->getPagado() )
    {
        Logger::log("Error al insertat factura, no se ha liqueidado la venta {$args['id_venta']}");
        die( '{"success": false, "reason": "No se ha liquidado la venta ' . $args['id_venta'] . '" }' );
    }

    $factura_venta = new FacturaVenta();
    $factura_venta->setIdVenta( $args['id_venta'] );

    try{
		FacturaVentaDAO::save( $factura_venta);
	}catch(Exception $e){
        Logger::log("Error al guardar la factura : " . $e);
	    die( '{"success": false, "reason": "Error al guardar la factura" }' );
	}
    
    Logger::log("Se ha creado la factura {$factura_venta->getFolio()} para la venta {$args['id_venta']}.");
    printf( '{ "succes" : true }' );

}

function eliminarFactura( $args ){

    if( !isset( $args['folio'] ) )
    {
        Logger::log("Error al eliminar factura, no se ha especificado el folio.");
        die('{ "success" : "false" , "reason" : "Error al eliminar factura, no se ha especificao el folio." }' );
    }


    $factura = FacturaVentaDAO::getByPK( $args['folio'] );

    if( is_null( $factura ) )
    {
        Logger::log("Error al eliminar factura, no se tiene registro del folio {$args['folio']}.");
        die( '{ "succes" : "false" , "reason" : "La factura que desea eliminar no existe."}' );
    }
        
         
    if( FacturaVentaDAO::delete( $factura ) > 0)
    {
        Logger::log("Se elimino correctamente la factura.");
        printf( '{ "succes" : true }' );
    }
    else
    {
        Logger::log("Error al eliminar la factura, se ejecuto la consulta, pero no se afecto ningun registro.");
        die( '{ "succes" : "false" , "reason" : "No se pudo eliminar la factura."}' );
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
			"escala" => $productoData->getEscala(),
            "descripcion" => $productoData->getDescripcion(),
            "cantidad" => $dV->getCantidad(),
            "cantidadProc" => $dV->getCantidadProcesada(),
            "precio" => $dV->getPrecio(),
            "precioProc" => $dV->getPrecioProcesada()
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

        $producto -> descripcion = $productoData -> getDescripcion();

        array_push( $array_detalle_venta, $producto -> asArray() );

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

    //verificamos que la venta exista
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
			
			//validamos que no haya transcurrido mas del tiempo debido para poder eliminar la venta
			
		    $fecha_ultima_venta =  strtotime( $venta -> getFecha() );
		    
		    $fecha_actual = time();

		    
		    if( ( $fecha_actual  - $fecha_ultima_venta ) >  POS_ELIMINATION_TIME  )
		    {
		        Logger::log( 'Error al eliminar la venta, han pasado mas de ' . ( POS_ELIMINATION_TIME / 60 ) . ' minutos desde que se realizo la venta.' );
                die( '{ "success" : false , "info" : "elimination_time", "reason" : "Error al eliminar la venta, han pasado mas de ' . ( POS_ELIMINATION_TIME / 60 ) . ' minutos desde que se realizo la venta ' . $venta -> getIdVenta() . '."}' );
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
