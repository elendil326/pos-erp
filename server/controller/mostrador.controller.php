<?php

/**
 *  Controller para mostrador
 */
 
require_once("../server/model/ventas.dao.php");
require_once("../server/model/cliente.dao.php");
require_once("../server/model/detalle_venta.dao.php");
require_once("../server/model/detalle_inventario.dao.php");
require_once("../server/model/factura_venta.dao.php");
require_once("logger.php");

/*
 * Crea una factura para este objeto venta
 * @param Venta
 * @returns verdadero si tuve exito, falso de lo contrario
 * */
function insertarFacturaVenta ( $venta )
{
	//buscar que no exista antes
	$fv = new FacturaVenta();
	$fv->setIdVenta( $venta->getIdVenta() );
	
	$res = FacturaVentaDAO::search( $fv );
	
	if(count($res) != 0){
		return false;
	}
	
    try{
    	FacturaVentaDAO::save( $fv );
    }catch(Exception $e){
        Logger::log("Error al salvar la factura a la venta");
    }

	
}


/*
 * @param DetalleVenta [] 
 * @returns verdadero si existen suficientes productos para satisfacer la demanda en esta sucursal para el arreglo de DetalleVenta, falso si no
 * */
function revisarExistencias ( $productos )
{

	foreach( $productos as $p ){
		$i = DetalleInventarioDAO::getByPK( $p->getIdProducto(), $_SESSION['sucursal'] );
		if( $i->getExistencias() < $p->getCantidad() ){
			return false;
		}
	}
	
	return true;
}




/*
 * Descuenta del inventario los productos dados en el arreglo detalle venta, y tambien inserta esos detalles venta en la base de datos
 * @param DetalleVenta[] 
 * @return verdadero si tuvo exito, falso si no fue asi
 * 
 * */
function descontarInventario ( $productos )
{
	
	foreach($productos as $dVenta)
    {

		
		//insertar el detalle de la venta
        try{
            if (!DetalleVentaDAO::save($dVenta)){
                return false;
            } 
        }catch(Exception $e){
            die( '{"success": false, "reason": "' . $e . '" }' );
        }



		//descontar del inventario
		$dInventario = DetalleInventarioDAO::getByPK( $dVenta->getIdProducto(), $_SESSION['sucursal'] );
		$dInventario->setExistencias( $dInventario->getExistencias() - $dVenta->getCantidad() );
		try{
			DetalleInventarioDAO::save( $dInventario );			
		}catch(Exception $e){
			return false;
		}

		
    }

	return true;
	
}



/*
 * Realizar una venta a un cliente
 * 
 * 
 * */
function vender( $args ){

    Logger::log("Iniciando proceso de venta...");

    if(!isset($args['payload']))
    {
        Logger::log("Sin parametros para realizar venta");
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    }

    try{
        $data = json_decode( $args['payload'] );
    }catch(Exception $e){
        Logger::log("json invalido para realizar venta" . $e);
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if($data == null){
        die( '{"success": false, "reason": "Parametros invalidos. El resultado es nulo." }' );
    }

	//verificamos que todos estos productos esten en existencias
	//creamos un arreglo de objetos DetalleVenta
	//hasta aqui ya esta creada la venta, ahora sigue llenar el detalle de la venta
    $productos = $data->items;
	$detallesVenta = array();
	$subtotal = 0.0;
	
    //verificar que $productos sea un array
    if(!is_array($productos)){
        Logger::log("parametro de producotos invalido, no es un arreglo");
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    foreach($productos as $producto)
    {
		$subtotal += ( $producto->cantidad * $producto->precioVenta );
        $dv = new DetalleVenta();
        $dv->setIdProducto( $producto->productoID );
        $dv->setCantidad( $producto->cantidad );
        $dv->setPrecio( $producto->precioVenta );

		array_push($detallesVenta, $dv );
    }
	
	
	if(!revisarExistencias( $detallesVenta )){
        Logger::log("No hay existencias para satisface la demanda");
		die('{"success": false, "reason": "No hay suficiente producto para satisfacer la demanda. Intente de nuevo." }');
	}
	


    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario( $_SESSION['userid'] );
    $venta->setTotal( 0 );
    $venta->setIdSucursal( $_SESSION['sucursal'] );
    $venta->setIp( $_SERVER['REMOTE_ADDR']  );
    $venta->setPagado( 0 );

    /*
     * Si el cliente es nulo, entonces es caja comun
     * */
    if($data->cliente == NULL)
    {

        $venta->setIdCliente( $_SESSION['sucursal'] * -1 );
        $venta->setTipoVenta( "contado" );
        $venta->setDescuento( 0 );
		$descuento = 0;
		
        try{
            if (VentasDAO::save($venta)){
                $id_venta =  $venta->getIdVenta();
            }else{
                Logger::log("No se afectaron columnas al insertar venta");
                die( '{"success": false, "reason": "No se pudo registrar la venta" }' );
            }

        }catch(Exception $e){
	        Logger::log("Error al insertar la venta " . $e);
            die( '{"success": false, "reason": "' . $e . '" }' );

        }

    }
    else
    {

        //entra si es un cliente corriente

        $venta->setIdCliente( $data->cliente->id_cliente );
        $venta->setTipoVenta( $data->tipoDeVenta );

        try{
            if ( $cliente = ClienteDAOBase::getByPK( $data->cliente->id_cliente ) ) 
            {
                $descuento = $cliente->getDescuento();
            } 
            else 
            {
                die( '{"success": false, "reason": "No se tienen registros sobre el cliente ' . $data->cliente->id_cliente . '" }' );
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



		if($data->factura){
			insertarFacturaVenta($venta);
		}
		
    }

    //hasta aqui ya esta creada la venta, ahora sigue llenar el detalle de la venta
    $productos = $data->items;


    //TODO:Esto estaria muy bien si estuviera con una transaccion y si no se pudiera guardar algun producto en el detalle de la venta, se disparara un rollover
	
	//insertar el id de la venta
    foreach($detallesVenta as $dv)
    {
        $dv->setIdVenta( $id_venta );
    }


	//insertar detalles de la venta y descontar de inventario
	if(!descontarInventario( $detallesVenta )){
		die( '{"success": false, "reason": "Porfavor intente de nuevo." }' );
	}


    //ya que se tiene el total de la venta se actualiza el total de la venta
    $venta->setSubtotal( $subtotal );
    $total = ( $subtotal - ( ($subtotal * $descuento) / 100 ) );
    $venta->setTotal( $total );


    //si la venta es de contado, hay que liquidarla
    if( $venta->getTipoVenta() == "contado" ){
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


    Logger::log("Venta exitosa !");
}

switch( $args['action'] ){

    case 100:
		//realizar una venta
        vender($args);
    break;

}

?>
