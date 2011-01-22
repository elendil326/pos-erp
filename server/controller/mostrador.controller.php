<?php

/**
 *  Controller para mostrador
 */
 
require_once("model/ventas.dao.php");
require_once("model/cliente.dao.php");
require_once("model/detalle_venta.dao.php");
require_once("model/detalle_inventario.dao.php");
require_once("model/factura_venta.dao.php");
require_once("model/usuario.dao.php");
require_once("model/inventario_maestro.dao.php");
require_once("model/compra_proveedor.dao.php");
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
function revisarExistenciasSucursal( $productos )
{

	foreach( $productos as $p ){

        //verificamos que exista el producto en el detalle inventario
	    if( !( $i = DetalleInventarioDAO::getByPK( $p -> id_producto, $_SESSION['sucursal'] ) ) ){
            Logger::log("No se tiene registro del producto " . $p -> id_producto . " en esta sucursal." );
            DAO::transRollback();
            die( '{"success": false, "reason": "No se tiene registro del producto ' . $p -> id_producto . ' en esta sucursal.." }' );
        }
        
        if( $p -> procesado == true ){
		    //requiere producto procesado
		    if( $p -> cantidad_procesada > $i-> getExistenciasProcesadas() ){
		        return false;
		    }
		}else{
		    //requiere producto sin procesar
		    if( $p -> cantidad > $i-> getExistencias() ){
		        return false;
		    }
		}

	}
	
	return true;
}


/*
 * @param DetalleVenta [] 
 * @returns verdadero si existen suficientes productos para satisfacer la demanda en el inventario maestro
 * */
function revisarExistenciasAdmin ( $productos )
{
   
	foreach( $productos as $p ){
	
	    //verificamos que exista la compra al proveedor
	    if( !( CompraProveedorDAO::getByPK( $p->id_compra_proveedor) ) ){
            Logger::log("No se tiene registro de la compra " . $p->id_compra_proveedor . " al proveedor." );
            DAO::transRollback();
            die( '{"success": false, "reason": "No se tiene registro de la compra ' . $p->id_compra_proveedor . ' al proveedor." }' );
        }
	
	    //verificamos que en la comrpa se haya comprado el producto
		if( !( $i = InventarioMaestroDAO::getByPK( $p->id_producto, $p->id_compra_proveedor) ) ){
            Logger::log("No se tiene registro de la compra del producto " . $p->id_producto . " en la compra " . $p->id_compra_proveedor );
            DAO::transRollback();
            die( '{"success": false, "reason": "No se tiene registro de la compra del producto ' .  $p->id_producto . ' en la compra ' . $p->id_compra_proveedor . '." }' );
        }
		
		
		if( $p -> procesado == true ){
		    //requiere producto procesado
		    if( $p -> cantidad_procesada > $i-> getExistenciasProcesadas() ){
		        return false;
		    }
		}else{
		    //requiere producto sin procesar
		    if( $p -> cantidad > $i-> getExistencias() ){
		        return false;
		    }
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
            die( '{"success": false, "reason": "Error al guardar el detalle venta." }' );
        }



		//descontar del inventario
		$dInventario = DetalleInventarioDAO::getByPK( $dVenta->getIdProducto(), $_SESSION['sucursal'] );
		
		$dInventario->setExistencias( $dInventario->getExistencias() - $dVenta->getCantidad() );
		
		$dInventario->setExitenciasProcesadas( $dInventario->getExitenciasProcesadas() - $dVenta->getCantidadProcesada() );
		
		try{
			DetalleInventarioDAO::save( $dInventario );			
		}catch(Exception $e){
			return false;
		}

		
    }

	return true;
	
}



/**
  * Venta desde la sucursal.
  *
  * Realiza una venta desde la sucursal, descontando
  * los productos a vender del inventario de la sucursal.
  *
  *
  * Formato de json.
  * <code>
  *        {
  *             "id_cliente": int | null,
  *             "tipo_venta": "contado" | "credito",
  *             "tipo_pago": "tarjeta" | "cheque" | "efectivo",
  *             "factura": false | true,
  *             "items": [
  *                 {
  *                     "id_producto": int,
  *                     "procesado": true | false,
  *                     "precio":float,
  *                     "cantidad": float
  *                 }
  *             ]
  *        }
  * </code>
  * 
  * {"id_cliente": 1,"tipo_venta": "contado","tipo_pago":"efectivo","factura": false,"items": [{"id_producto": 1,"procesado": true,"precio": 5.5,"cantidad": 5}]}
  **/
function vender( $args ){

    Logger::log("Iniciando proceso de venta (sucursal)");

    if( ! isset ( $args['payload'] ) )
    {
        Logger::log( "Sin parametros para realizar venta (sucursal)" );
        die('{"success": false, "reason": "No hay parametros para realizar la venta." }');
    } 

    try{
        $data = parseJSON( $args['payload'] );
    }catch(Exception $e){
        Logger::log("json invalido para realizar venta : " . $e);
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if($data == null){
        Logger::log("el parseo del json de la venta resulto en un objeto nulo");
        die( '{"success": false, "reason": "Parametros invalidos. El objeto es nulo." }' );
    }
	
	//verificamos que se manden todos los parametros necesarios
	
	if( !( isset( $data -> tipo_venta ) && isset( $data -> factura ) && isset( $data -> items ) ) ){	
	    Logger::log("Falta uno o mas parametros");
        die( '{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }' );
	}
	
    //verificar que $data->items  sea un array
    if(!is_array( $data->items )){
        Logger::log("data -> items no es un array de productos");
        die( '{"success": false, "reason": "No se genero correctamente las especificaciones de los productos a vender." }' );
    }
    
    //verificamos que $data->items almenos tenga un producto
    if( count( $data->items ) <= 0 ){
        Logger::log("data -> items no contiene ningun producto");
        die( '{"success": false, "reason": "No se especifico ningun producto para generar una nueva venta." }' );
    }
    
    //verificamos que cada objeto de producto tenga los parametros necesarios
    foreach( $data->items as $item ){
    
        if( !( isset( $item -> id_producto ) && isset( $item -> procesado ) && isset( $item -> precio ) && isset( $item -> cantidad ) ) ){
            Logger::log("Uno o mas objetos de data -> items no tiene el formato correcto");
            die( '{"success": false, "reason": "No se genero correctamente la descripcion de uno o mas productos." }' );
        }
    
    }
    
   
    /*
    * Condensamos los productos
    * iteramos el array de productos enviado ($data -> items)  e insertandolos en otro array, este nuevo array contiene objetos 
    * de tipo standard se especifican la cantidad de producto procesado y sin procesar, antes de insertar un nuevo producto de 
    * $data -> items antes revisamos que no haya un producto con el mismo id, y de haberlo sumamos las cantidades del producto
    * para que solo haya un mismo producto a la ves y asi pudiendo consegir un solo registro de un mismo producto con cantidaddes
    * de producto procesado o sin procesar.
    */

    $array_items = array();
    //insertamos el primer producto de item
    
    $item = new stdClass();
    $item -> id_producto = $data->items[0] -> id_producto;
    $item -> procesado = $data -> items[0] -> procesado;
    
    if( $data->items[0] -> cantidad <= 0 ){
        Logger::log("La cantidad de los productos debe ser mayor que cero.");
        die('{"success": false, "reason": "La cantidad de los productos debe ser mayor que cero." }');
    }
        
    if(  $data->items[0] -> precio <= 0 ){
        Logger::log("El precio de los productos debe ser mayor que cero.");
        die('{"success": false, "reason": "El precio de los productos debe ser mayor que cero." }');
    }
    
    if( $data -> items[0] -> procesado == true ){

         $item -> cantidad_procesada = $data->items[0] -> cantidad;
         $item -> precio_procesada = $data->items[0] -> precio;
         $item -> cantidad = 0;
         $item -> precio = 0;
    }else{
        $item -> cantidad_procesada = 0;
        $item -> precio_procesada = 0;
        $item -> cantidad = $data->items[0] -> cantidad;
        $item -> precio = $data->items[0] -> precio;
    }

    //insertamos el primer producto
    array_push( $array_items, $item  );
    
    //recorremos a $data->items para condensar el array de productos
    for( $i = 1; $i < count($data->items); $i++ ){
    
        //iteramos el $obj_items 
        
        if( $data->items[$i] -> cantidad <= 0 ){
            Logger::log("La cantidad de los productos debe ser mayor que cero.");
            die('{"success": false, "reason": "La cantidad de los productos debe ser mayor que cero." }');
        }
            
        if(  $data->items[$i] -> precio <= 0 ){
            Logger::log("El precio de los productos debe ser mayor que cero.");
            die('{"success": false, "reason": "El precio de los productos debe ser mayor que cero." }');
        }
        
        foreach( $array_items as $item ){
            
            if(  $data->items[$i]  -> id_producto == $item -> id_producto ){

                  //si se encuentra ese producto en el arreglo de objetos
                if( $data->items[$i]->procesado == true ){
                     $item -> cantidad_procesada += $data->items[$i] -> cantidad;       
                                   
                     if( $item -> precio_procesada != 0 && $item -> precio_procesada != $data->items[$i]-> precio){
                        Logger::log("Selecciono dos productos iguales, pero con diferente precio.");
		                die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item -> id_producto . ' - PROCESADO, pero con diferente precio." }');
                     }
                     
                     $item -> precio_procesada = $data->items[$i]-> precio;
                     
                }else{
                    $item -> cantidad += $data->items[$i] -> cantidad;
                    
                    if( $item -> precio != 0 && $item -> precio != $data->items[$i]-> precio){
                        Logger::log("Selecciono dos productos iguales, pero con diferente precio.");
		                die('{"success": false, "reason": "Selecciono dos o mas productos ' . $item -> id_producto . ' - ORIGINAL, pero con diferente precio." }');
                     }
                     
                     $item -> precio = $data->items[$i]-> precio;
                    
                }                                
            }else{
            
                //si no se encuentra el producto en el arreglo de objetos hay que crearlo
                $_item = new stdClass();
                $_item -> id_compra_proveedor = $data->items[$i] -> id_compra_proveedor;
                $_item -> id_producto = $data->items[$i]-> id_producto;
                
                if( $data->items[$i]->procesado == true ){
                     $_item -> cantidad_procesada = $data->items[$i] -> cantidad;
                     $_item -> precio_procesada = $data->items[$i] -> precio;
                     $_item -> cantidad = 0;
                     $_item -> precio = 0;
                }else{
                    $_item -> cantidad_procesada = 0;
                    $_item -> precio_procesada = 0;
                    $_item -> cantidad = $data->items[$i] -> cantidad;
                    $_item -> precio = $data->items[$i] -> precio;
                }                                
                array_push( $array_items, $_item  );
            }
            
        }
        
    }
	
	 //revisamos si las existencias en el inventario de la sucursal para ver si satisfacen a las requeridas en la venta
    if(!revisarExistenciasSucursal( $array_items )){
        Logger::log("No hay existencias suficientes en el inventario de la suursal para satisfacer la demanda");
		die('{"success": false, "reason": "No hay existencias suficientes en el inventario de la suursal para satisfacer la demanda. Intente de nuevo." }');
	}
	

    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario( $_SESSION['userid'] );
    $venta->setTotal( 0 );
    $venta->setIdSucursal( $_SESSION['sucursal'] );
    $venta->setIp( $_SERVER['REMOTE_ADDR']  );
    $venta->setPagado( 0 );
    $venta->setLiquidada( 0 );
    $venta->setDescuento( 0 );
    $venta->setCancelada( 0 );
    
    if( !isset( $data -> id_cliente ) )
    {

        $venta->setIdCliente( $_SESSION['sucursal'] * -1 );
        $venta->setTipoVenta( "contado" );
        $venta->setDescuento( 0 );
		$descuento = 0;
		
    }else{
    
        //verificamos que el cliente exista
        if( !( $cliente = ClienteDAO::getByPK( $data -> id_cliente) ) ){
            Logger::log("No se tiene registro del cliente : " . $data -> id_cliente );
            die( '{"success": false, "reason": "Parametros invalidos 5." }' );
        }
        
        $venta->setIdCliente( $data->id_cliente );
        
        //verificamos que el tipo de venta sea valido
        switch( $data -> tipo_venta ){
       
            case 'credito':
                $venta -> setTipoVenta( $data -> tipo_venta );
            break;
            
            case 'contado':
                $venta -> setTipoVenta( $data -> tipo_venta );
            break;
            
            default:
                Logger::log( "El tipo de venta no es valido : " . $data -> tipo_venta );
                die( '{"success": false, "reason": "El tipo de venta no es valido ' . $data -> tipo_venta . '." }' );
       
        }
        
        $descuento = $cliente->getDescuento();
        
        if($data->factura){
            insertarFacturaVenta($venta);
        }
    
    }
    
    if( isset( $data -> tipo_pago ) &&  $venta -> getTipoVenta( ) == "contado" ){    
        //verificamos que el tipo de pago sea valido
        switch( $data -> tipo_pago ){
       
            case 'efectivo':
                $venta -> setTipoPago( $data -> tipo_pago );
            break;
            
            case 'cheque':
                $venta -> setTipoPago( $data -> tipo_pago );
            break;
            
            case 'tarjeta':
                $venta -> setTipoPago( $data -> tipo_pago );
            break;
            
            default:
                Logger::log( "El tipo de pago no es valido : " . $data -> tipo_pago );
                die( '{"success": false, "reason": "El tipo de pago no es valido : ' . $data -> tipo_pago . '." }' );
       
        }
    }

    DAO::transBegin();	
        
    try
    {
        VentasDAO::save($venta);
        $id_venta =  $venta->getIdVenta();
    }          
    catch(Exception $e)
    {
        Logger::log( $e);
        DAO::transRollback();
        die( '{"success": false, "reason": "No se pudo registrar la venta." }' );       
    }


    //insertar detalles de la venta   
  	foreach($array_items as $producto)
    {
        $detalle_venta = new  DetalleVenta(); 
        $detalle_venta -> setIdVenta( $id_venta );
        $detalle_venta -> setIdProducto( $producto -> id_producto );
        $detalle_venta -> setCantidad( $producto -> cantidad );
        $detalle_venta -> setPrecio( $producto -> precio );
        $detalle_venta -> setCantidadProcesada( $producto -> cantidad_procesada );
        $detalle_venta -> setPrecioProcesada( $producto -> precio_procesada );
        
         try{
            DetalleVentaDAO::save( $detalle_venta );
        }catch(Exception $e){
            DAO::transRollback();
	        Logger::log( $e);
            die( '{"success": false, "reason": "Error, al guardar el detalle venta." }' );
        }
    }

     //descontamos del inventario el pedido
    $subtotal = 0;

    foreach( $array_items as $producto ){	
    
		$detalle_inventario = DetalleInventarioDAO::getByPK( $producto -> id_producto, $_SESSION['sucursal'] );	
			
		$detalle_inventario -> setExistenciasProcesadas( $detalle_inventario -> getExistenciasProcesadas() - $producto -> cantidad_procesada ); 
	    $detalle_inventario -> setExistencias( $detalle_inventario -> getExistencias() - $producto -> cantidad);
		
		$subtotal += ( ( $producto -> cantidad_procesada * $producto -> precio_procesada ) + ( $producto -> cantidad * $producto -> precio ) );
		
		try{
            DetalleInventarioDAO::save( $detalle_inventario );
        }catch(Exception $e){
            DAO::transRollback();
	        Logger::log( "Error, al descontar el pedido de productos del inventario de la sucursal, exception : " . $e);
            die( '{"success": false, "reason": "Error, al descontar el pedido de productos del inventario de la sucursal" }' );
        }
        
	}	    
	
    //ya que se tiene el total de la venta se actualiza el total de la venta
    $venta->setSubtotal( $subtotal );
    $total = ( $subtotal - ( ( $subtotal * $descuento ) / 100 ) );
    $venta->setTotal( $total );

    //si la venta es de contado, hay que liquidarla
    if( $venta -> getTipoVenta() == "contado" ){
        $venta -> setPagado( $total );
        $venta -> setLiquidada( 1 );
    }

    try
    {
        if ( VentasDAO::save($venta) )
        {
			$empleado = UsuarioDAO::getByPK( $venta->getIdUsuario() );
			
            printf('{"success": true, "id_venta":%s, "empleado":"%s"}', $venta->getIdVenta(), $empleado->getNombre() );
        }
        else 
        {
            DAO::transRollback();
            die( '{"success": false, "reason": "No se pudo actualizar el total de la venta" }' );

        }
    }
    catch(Exception $e)
    {
        DAO::transRollback();
        Logger::log($e);
        die( '{"success": false, "reason": "Intente de nuevo." }' );
    }

    DAO::transEnd();

    Logger::log("Proveso de venta (sucursal), termino con exito!! id_venta : {$id_venta}.");

}//vender


/**
  * Venta desde centro de distribucion.
  *
  * Realiza una venta desde el centro de distribucion, descontando
  * los productos a vender del inventario maestro.
  *
  *
  * Formato de json.
  * <code>
  *        {
  *             "id_cliente": int,
  *             "tipo_venta": "contado" | "credito",
  *             "tipo_pago": "tarjeta" | "cheque" | "efectivo",
  *             "factura": false | true,
  *             "items": [
  *                 {
  *                     "id_producto": int,
  *                     "id_compra_proveedor": int,
  *                     "procesado": true | false,
  *                     "precio": float,
  *                     "cantidad": float
  *                 }
  *             ]
  *        }
  * </code>
  *
  *
  **/
function venderAdmin( $args ){

    Logger::log("Iniciando proceso de venta (admin)");

    if( ! isset ( $args['payload'] ) )
    {
        Logger::log( "Sin parametros para realizar venta (admin)" );
        die('{"success": false, "reason": "No hay parametros para realizar la venta." }');
    } 

    try{
        $data = parseJSON( $args['payload'] );
    }catch(Exception $e){
        Logger::log("json invalido para realizar venta : " . $e);
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if($data == null){
        Logger::log("el parseo del json de la venta resulto en un objeto nulo");
        die( '{"success": false, "reason": "Parametros invalidos. El objeto es nulo." }' );
    }
	
	//verificamos que se manden todos los parametros necesarios
	
	if( !( isset( $data -> id_cliente ) && isset( $data -> tipo_venta ) && isset( $data -> factura ) && isset( $data -> items ) ) ){	
	    Logger::log("Falta uno o mas parametros");
        die( '{"success": false, "reason": "Verifique sus datos, falta uno o mas parametros." }' );
    
	}
	
    //verificar que $data->items  sea un array
    if(!is_array( $data->items )){
        Logger::log("data -> items no es un array de productos");
        die( '{"success": false, "reason": "No se generaron correctamente las descripciones de los productos para la venta." }' );
    }
    
    //verificamos que $data->items almenos tenga un producto
    if( count( $data->items ) <= 0 ){
        Logger::log("data -> items no contiene ningun producto");
        die( '{"success": false, "reason": "No se envio ningun producto para generar una nueva venta." }' );
    }
    
    //verificamos que cada objeto de producto tenga los parametros necesarios
    foreach( $data->items as $item ){
    
        if( !( isset( $item -> id_producto ) && isset( $item -> id_compra_proveedor ) && isset( $item -> procesado ) && isset( $item -> precio ) && isset( $item -> cantidad ) ) ){
            Logger::log("Uno o mas objetos de data -> items no tiene el formato correcto");
            die( '{"success": false, "reason": "No se genero correctamente la descripcion de uno o mas productos." }' );
        }
    
    }
    
    //verificamos que el cliente exista
    if( !( $cliente = ClienteDAO::getByPK( $data -> id_cliente) ) ){
        Logger::log("No se tiene registro del cliente : " . $data -> id_cliente );
        die( '{"success": false, "reason": "No se tiene registro del cliente ' . $data -> id_cliente . '." }' );
    }
    

    /*
    * Condensamos los productos
    * iteramos el array de productos enviado ($data -> items)  e insertandolos en otro array, este nuevo array contiene objetos 
    * de tipo standard se especifican la cantidad de producto procesado y sin procesar, antes de insertar un nuevo producto de 
    * $data -> items antes revisamos que no haya un producto con el mismo id, y de haberlo sumamos las cantidades del producto
    * para que solo haya un mismo producto a la ves y asi pudiendo consegir un solo registro de un mismo producto con cantidaddes
    * de producto procesado o sin procesar.
    */

    $array_items = array();
    //insertamos el primer producto de item
    
    $item = new stdClass();
    $item -> id_compra_proveedor = $data->items[0] -> id_compra_proveedor;
    $item -> id_producto = $data->items[0] -> id_producto;
    $item -> procesado = $data -> items[0] -> procesado;
    
    if( $data -> items[0] -> procesado == true ){
         $item -> cantidad_procesada = $data->items[0] -> cantidad;
         $item -> precio_procesada = $data->items[0] -> precio;
         $item -> cantidad = 0;
         $item -> precio = 0;
    }else{
        $item -> cantidad_procesada = 0;
        $item -> precio_procesada = 0;
        $item -> cantidad = $data->items[0] -> cantidad;
        $item -> precio = $data->items[0] -> precio;
    }

    //insertamos el primer producto
    array_push( $array_items, $item  );
    
    //recorremos a $data->items para condensar el array de productos
    for( $i = 1; $i < count($data->items); $i++ ){
    
        //iteramos el $obj_items 
        
        foreach( $array_items as $item ){
            
            if(  $data->items[$i]  -> id_producto == $item -> id_producto ){

                  //si se encuentra ese producto en el arreglo de objetos
                if( $data->items[$i]->procesado == true ){
                     $item -> cantidad_procesada += $data->items[$i] -> cantidad_procesada;                     
                     $item -> precio_procesada += $data->items[$i]-> precio_procesada;
                     
                }else{
                    $item -> cantidad += $data->items[$i] -> cantidad;
                    $item -> precio += $data->items[$i] -> precio;
                }                                
            }else{
            
                //si no se encuentra el producto en el arreglo de objetos hay que crearlo
                $_item = new stdClass();
                $_item -> id_compra_proveedor = $data->items[$i] -> id_compra_proveedor;
                $_item -> id_producto = $data->items[$i]-> id_producto;
                
                if( $data->items[$i]->procesado == true ){
                     $_item -> cantidad_procesada = $data->items[$i] -> cantidad;
                     $_item -> precio_procesada = $data->items[$i] -> precio;
                     $_item -> cantidad = 0;
                     $_item -> precio = 0;
                }else{
                    $_item -> cantidad_procesada = 0;
                    $_item -> precio_procesada = 0;
                    $_item -> cantidad = $data->items[$i] -> cantidad;
                    $_item -> precio = $data->items[$i] -> precio;
                }                                
                array_push( $array_items, $_item  );
            }
            
        }
        
    }
    

    //revisamos si las existencias en el inventario maestro satisfacen a las requeridas en la venta
    if(!revisarExistenciasAdmin( $data->items )){
        Logger::log("No hay existencias suficientes en el Inventario maestro para satisfacer la demanda");
		die('{"success": false, "reason": "No hay suficiente producto en el Inventario Maestro para satisfacer la demanda. Intente de nuevo." }');
	}

    $productos = $array_items;
	$detallesVenta = array();
	$subtotal = 0.0;

    foreach($productos as $producto)
    {
    
		$subtotal += (  ( $producto -> cantidad * $producto -> precio ) + ( $producto -> cantidad_procesada * $producto -> precio_procesada ) );
        $dv = new DetalleVenta();
        $dv -> setIdProducto( $producto -> id_producto );
        
        $dv->setCantidad( $producto -> cantidad );
        $dv->setPrecio( $producto -> precio );
        $dv->setCantidadProcesada( $producto->cantidad_procesada );
        $dv->setPrecioProcesada( $producto->precio_procesada );
        
		array_push($detallesVenta, $dv );
		
    }//foreach

    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario( $_SESSION['userid'] );
    $venta->setTotal( 0 );
    $venta->setIdSucursal( $_SESSION['sucursal'] );
    $venta->setIp( $_SERVER['REMOTE_ADDR']  );
    $venta->setPagado( 0 );
    $venta->setLiquidada( 0 );
    
    $venta->setCancelada( 0 );
   
   //verificamos que el tipo de venta sea valido
    switch( $data -> tipo_venta ){
   
        case 'credito':
            $venta -> setTipoVenta( $data -> tipo_venta );
        break;
        
        case 'contado':
            $venta -> setTipoVenta( $data -> tipo_venta );
        break;
        
        default:
            Logger::log( "El tipo de venta no es valido : " . $data -> tipo_venta );
            die( '{"success": false, "reason": "El tipo de venta no es valido ' . $data -> tipo_venta . '." }' );
   
    }
    
    if( isset( $data -> tipo_pago ) && $data -> tipo_venta == "contado" ){    
    
        //verificamos que el tipo de pago sea valido
        switch( $data -> tipo_pago ){
       
            case 'efectivo':
                $venta -> setTipoPago( $data -> tipo_pago );
            break;
            
            case 'cheque':
                $venta -> setTipoPago( $data -> tipo_pago );
            break;
            
            case 'tarjeta':
                $venta -> setTipoPago( $data -> tipo_pago );
            break;
            
            default:
                Logger::log( "El tipo de pago no es valido : " . $data -> tipo_pago );
                die( '{"success": false, "reason": "El tipo de pago no es valido : ' . $data -> tipo_pago . '." }' );
       
        }
        
    }

    $venta->setIdCliente( $data->id_cliente );

    $venta->setDescuento( $cliente->getDescuento() );

    DAO::transBegin();	
        
    try
    {
        VentasDAO::save($venta);
        $id_venta =  $venta->getIdVenta();
    }          
    catch(Exception $e)
    {
        Logger::log( $e);
        DAO::transRollback();
        die( '{"success": false, "reason": "No se pudo registrar la venta." }' );       
    }

    if($data->factura){
        insertarFacturaVenta($venta);
    }

    //hasta aqui ya esta creada la venta, ahora sigue llenar el detalle de la venta
    $productos = $data->items;

	//insertar detalles de la venta
  	foreach($detallesVenta as $dVenta)
    {
    
        $dVenta->setIdVenta( $id_venta );

         try{

            DetalleVentaDAO::save($dVenta);
        }catch(Exception $e){

            DAO::transRollback();
	        Logger::log( $e);
            die( '{"success": false, "reason": "Error, al guardar el detalle venta." }' );
        }

    }

    //descontamos del inventario el pedido
    foreach( $productos as $producto ){	
		$inventario_maestro = InventarioMaestroDAO::getByPK( $producto -> id_producto, $producto -> id_compra_proveedor);		
		if( $producto -> procesado == true ){		
		    //requiere producto procesado
		   $inventario_maestro-> setExistenciasProcesadas( $inventario_maestro-> getExistenciasProcesadas() - $producto -> cantidad ); 
		}else{		
		    //requiere producto sin procesar
		  $inventario_maestro -> setExistencias( $inventario_maestro -> getExistencias() - $producto -> cantidad);
		}					
		try{
            InventarioMaestroDAO::save( $inventario_maestro );
        }catch(Exception $e){
            DAO::transRollback();
	        Logger::log( "Error, al descontar el pedido de productos del inventario maestro, exception : " . $e);
            die( '{"success": false, "reason": "Error, al descontar el pedido de productos del inventario maestro" }' );
        }
	}	    
        
    //ya que se tiene el total de la venta se actualiza el total de la venta
    $venta->setSubtotal( $subtotal );
    $total = ( $subtotal - ( ( $subtotal * $cliente->getDescuento() ) / 100 ) );
    $venta->setTotal( $total );

    //si la venta es de contado, hay que liquidarla
    if( $venta->getTipoVenta() == "contado" ){
        $venta->setPagado( $total );
    }

    try
    {
        if ( VentasDAO::save($venta) )
        {
			$empleado = UsuarioDAO::getByPK( $venta->getIdUsuario() );
			
            printf('{"success": true, "id_venta":%s, "empleado":"%s"}', $venta->getIdVenta(), $empleado->getNombre() );
        }
        else 
        {
            DAO::transRollback();
            die( '{"success": false, "reason": "No se pudo actualizar el total de la venta" }' );
        }
    }
    catch(Exception $e)
    {
        DAO::transRollback();
        Logger::log($e);
        die( '{"success": false, "reason": "Intente de nuevo." }' );
    }

    DAO::transEnd();

    Logger::log("Proveso de venta (admin), termino con exito!! id_venta : {$id_venta}.");


}//venderAdmin

if( isset( $args['action'] ) ){
    switch( $args['action'] ){

        case 100:
		    //realizar una venta desde una sucursal
            vender($args);
        break;	

        case 101:
            //realizar una venta desde el admin
            venderAdmin( $args );
        break;
        
    }
}

?>



