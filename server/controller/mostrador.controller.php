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
		/*if( $i->getExistencias() < $p->getCantidad() ){
			return false;
		}*/
		
		
		if( $p->getCantidad() > 0){
		
		//    echo '$p->getCantidad() > 0 : ' . $p->getCantidad();
		
		    //si entra aqui requiere existencias originales
		    if( $i->getExistencias() < $p->getCantidad() )
			    return false;
		}
		
		if(  $p->getCantidadProcesada() > 0 ){
		
		    //echo '$p->getCantidadProcesada() > 0 : ' . $p->getCantidadProcesada();
		
		    //si entra aqui requiere existencias procesadas
		    if( $i->getExitenciasProcesadas() < $p->getCantidadProcesada() )
			    return false;
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
	
		$i = InventarioMaestroDAO::getByPK( $p->id_producto, $p->id_compra_proveedor);
		
		if( $p -> procesado == true ){
		    //requiere producto procesado
		    if( $cantidad > $i-> getExistenciasProcesadas() ){
		        return false;
		    }
		}else{
		    //requiere producto sin procesar
		    if( $cantidad > $i-> getExistencias() ){
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
            die( '{"success": false, "reason": "' . $e . '" }' );
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



/*
 * Realizar una venta a un cliente
 * 
 * 
 * */
function vender( $args ){

    Logger::log("Iniciando proceso de venta");

    DAO::transBegin();

    if(!isset($args['payload']))
    {
        Logger::log("Sin parametros para realizar venta");
        DAO::transRollback();
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
    } 

    try{
        $data = parseJSON( $args['payload'] );
    }catch(Exception $e){
        Logger::log("json invalido para realizar venta" . $e);
        DAO::transRollback();
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    //var_dump( $args['payload'] );  
    //var_dump( json_decode( $args['payload']  ) );  

    if($data == null){
        Logger::log("objeto invalido para vender");
        DAO::transRollback();
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
        DAO::transRollback();
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }



    foreach($productos as $producto)
    {
		$subtotal += ( $producto->cantidad * $producto->precioVenta );
        $dv = new DetalleVenta();
        $dv->setIdProducto( $producto->productoID );
        
        if( $producto-> tipoProducto == "procesado" ){
            $dv->setCantidad( 0 );
            $dv->setCantidadProcesada( $producto->cantidad );
        }else{
            $dv->setCantidad( $producto->cantidad );
            $dv->setCantidadProcesada( 0  );
        }
        
        $dv->setPrecio( $producto->precioVenta );

		array_push($detallesVenta, $dv );
    }
	
	if(!revisarExistencias( $detallesVenta )){
        Logger::log("No hay existencias para satisfacer la demanda");
        DAO::transRollback();
		die('{"success": false, "reason": "No hay suficiente producto para satisfacer la demanda. Intente de nuevo." }');
	}
	


    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario( $_SESSION['userid'] );
    $venta->setTotal( 0 );
    $venta->setIdSucursal( $_SESSION['sucursal'] );
    $venta->setIp( $_SERVER['REMOTE_ADDR']  );
    $venta->setPagado( 0 );
    
    $venta->setCancelada( 0 );
    
     if ( isset($data->tipoDePago) &&  $data->tipoDePago =! null ){
        $venta->setTipoPago( $data->tipoDePago );
     }

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

            VentasDAO::save($venta);
        }catch(Exception $e){

            DAO::transRollback();
	        Logger::log( $e);
            die( '{"success": false, "reason": "Error, intente de nuevo." }' );
        }

        $id_venta =  $venta->getIdVenta();
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
                DAO::transRollback();
                die( '{"success": false, "reason": "No se tienen registros sobre el cliente ' . $data->cliente->id_cliente . '" }' );
            }
        }
        catch(Exception $e)
        {
            DAO::transRollback();
            Logger::log($e);
            die( '{"success": false, "reason": "Intente de nuevo." }' );
        }

        $venta->setDescuento( $descuento );

        try{
            if (VentasDAO::save($venta)) 
            {
                $id_venta =  $venta->getIdVenta();
            } 
            else 
            {
                DAO::transRollback();
                die( '{"success": false, "reason": "No se pudo registrar la venta" }' );
            }
        }
        catch(Exception $e)
        {
            DAO::transRollback();
            Logger::log($e);
            die( '{"success": false, "reason": "Intente de nuevo." }' );
        }



		if($data->factura){
			insertarFacturaVenta($venta);
		}
		
    }

    //hasta aqui ya esta creada la venta, ahora sigue llenar el detalle de la venta
    $productos = $data->items;



	
	//insertar el id de la venta
    foreach($detallesVenta as $dv)
    {
        $dv->setIdVenta( $id_venta );
    }


	//insertar detalles de la venta y descontar de inventario
  	foreach($detallesVenta as $dVenta)
    {

		
		//insertar el detalle de la venta
      
       /* try{
            if (!DetalleVentaDAO::save($dVenta)){
                return false;
            } 
        }catch(Exception $e){
            die( '{"success": false, "reason": "' . $e . '" }' );
        }*/


         try{

            DetalleVentaDAO::save($dVenta);
        }catch(Exception $e){

            DAO::transRollback();
	        Logger::log( $e);
            die( '{"success": false, "reason": "Error, al guardar el detalle venta." }' );
        }


		//descontar del inventario
		$dInventario = DetalleInventarioDAO::getByPK( $dVenta->getIdProducto(), $_SESSION['sucursal'] );

        if($dInventario->getExistencias() < $dVenta->getCantidad() ){
            Logger::log("No hay mas existencias del producto {$dVenta->getIdProducto()}.");
            DAO::transRollback();
        	die('{"success": false, "reason": "No hay suficiente producto para satisfacer la demanda. Intente de nuevo." }');
        }
        
         if($dInventario->getExitenciasProcesadas() < $dVenta->getCantidadProcesada() ){
            Logger::log("No hay mas existencias del producto {$dVenta->getIdProducto()}.");
            DAO::transRollback();
        	die('{"success": false, "reason": "No hay suficiente producto para satisfacer la demanda. Intente de nuevo." }');
        }

		$dInventario->setExistencias( $dInventario->getExistencias() - $dVenta->getCantidad() );

		try{
            Logger::log("Descontando {$dVenta->getCantidad()} productos del articulo {$dVenta->getIdProducto()}");
			DetalleInventarioDAO::save( $dInventario );
		}catch(Exception $e){
            Logger::log("Imposible descontar de inventario: {$e}");
            DAO::transRollback();
    		die( '{"success": false, "reason": "Porfavor intente de nuevo." }' );
		}

		
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

    Logger::log("Venta {$id_venta} exitosa !");


}//vender

function venderAdmin( $args ){

    Logger::log("Iniciando proceso de venta (admin)");

    DAO::transBegin();

    if(!isset($args['payload']))
    {
        Logger::log("Sin parametros para realizar venta (admin)");
        DAO::transRollback();
        die('{"success": false, "reason": "No hay parametros para realizar la venta." }');
    } 

    try{
        $data = parseJSON( $args['payload'] );
    }catch(Exception $e){
        Logger::log("json invalido para realizar venta : " . $e);
        DAO::transRollback();
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    if($data == null){
        Logger::log("el parseo del json de la venta resulto en un objeto nulo");
        DAO::transRollback();
        die( '{"success": false, "reason": "Parametros invalidos. El objeto es nulo." }' );
    }
	
    //verificar que $productos sea un array
    if(!is_array($productos)){
        Logger::log("parametro de productos invalido, no es un arreglo");
        DAO::transRollback();
        die( '{"success": false, "reason": "Parametros invalidos." }' );
    }

    /*
    * Condensamos los productos
    * iteramos el array de productos enviado ($data -> items)  e insertandolos en otro array, este nuevo array contiene objetos 
    * de tipo standard se especifican la cantidad de producto procesado y sin procesar, antes de insertar un nuevo producto de 
    * $data -> items antes revisamos que no haya un producto con el mismo id, y de haberlo sumamos las cantidades del producto
    * para que solo haya un mismo producto a la ves y asi pudiendo consegir un solo registro de un mismo producto con cantidaddes
    * de producto procesado o sin procesar.
    */

   /* $array_items = array();
    //insertamos el primer producto de item
    
    $item = new stdClass();
    $item -> id_compra_proveedor = $data->items[0] -> id_compra_proveedor;
    $item -> id_producto = $data->items[0] -> id_producto;
    
    if( items[0] ->procesado == true ){
         $item -> cantidad_procesada = $data->items[0] -> cantidad;
         $item -> precio = $data->items[0] -> 0;
         $item -> precio_procesado = $data->items[0] -> precio;
    }else{
        $item -> cantidad = $data->items[0] -> cantidad;
        $item -> precio = $data->items[0] -> precio;
        $item -> precio_procesado = 0;
    }
   
    
    array_push( $array_items, $item  );
    
    for( $i = 1; i < count($data->items); $i++ ){
    
        //iteramos el $obj_items 
        foreach( $array_items as $item ){
            if(  $data->items[$i]  -> id_producto == $item -> id_producto ){

                  //si se encuentra ese producto en el arreglo de objetos
                if( $data->items[$i]->procesado == true ){
                
                     $item -> cantidad_procesada += $data->items[$i] -> cantidad;                     
                     $item -> precio_procesado = $data->items[$i]-> precio;
                     
                }else{
                
                    $item -> cantidad += $data->items[$i] -> cantidad;
                    $item -> precio = $data->items[$i]] -> precio;
                    
                }
                
                //si no se encuentra el producto en el arreglo de objetos hay que crearlo
                $item = new stdClass();
                $item -> id_compra_proveedor = $data->items[$i] -> id_compra_proveedor;
                $item -> id_producto = $data->items[$i -> id_producto;
                
                if( items[$i] ->procesado == true ){
                     $item -> cantidad_procesada = $data->items[$i] -> cantidad;
                     $item -> precio = $data->items[$i] -> 0;
                     $item -> precio_procesado = $data->items[$i] -> precio;
                }else{
                    $item -> cantidad = $data->items[$i] -> cantidad;
                    $item -> precio = $data->items[$i] -> precio;
                    $item -> precio_procesado = 0;
                }
                
                array_push( $array_items, $item  );
                
            }
        }
    
        $objecto = new stdClass();
        
    }
    */

    //revisamos si las existencias en el inventario maestro satisfacen a las reqeuridas en la venta
    if(!revisarExistenciasAdmin( $data->items )){
        Logger::log("No hay existencias suficientes en el Inventario maestro para satisfacer la demanda");
        DAO::transRollback();
		die('{"success": false, "reason": "No hay suficiente producto en el Inventario Maestro para satisfacer la demanda. Intente de nuevo." }');
	}

    $productos = $data->items;
	$detallesVenta = array();
	$subtotal = 0.0;

    foreach($productos as $producto)
    {
    
		$subtotal += (  $producto -> cantidad * $producto -> precio  );
        $dv = new DetalleVenta();
        $dv -> setIdProducto( $producto -> id_producto );
        
        if( $producto-> procesado == true ){
            $dv->setCantidad( 0 );
            $dv->setCantidadProcesada( $producto->cantidad );
        }else{
            $dv->setCantidad( $producto->cantidad );
            $dv->setCantidadProcesada( 0 );
        }
        
        $dv->setPrecio( $producto->precioVenta );

		array_push($detallesVenta, $dv );
		
    }//foreach
	

    //inicializamos un objeto venta
    $venta = new Ventas();
    $venta->setIdUsuario( $_SESSION['userid'] );
    $venta->setTotal( 0 );
    $venta->setIdSucursal( $_SESSION['sucursal'] );
    $venta->setIp( $_SERVER['REMOTE_ADDR']  );
    $venta->setPagado( 0 );
    
    $venta->setCancelada( 0 );
   
    $venta -> setTipoVenta( $data -> tipo_venta );
    
     if( isset($data -> tipo_pago  ) )
        $venta -> setTipoPago( $data -> tipo_pago );

    /*
     * Si el cliente es nulo, entonces es caja comun
     * */
    

        //entra si es un cliente corriente

        $venta->setIdCliente( $data->id_cliente );

        try{
            if ( $cliente = ClienteDAOBase::getByPK( $data->cliente->id_cliente ) ) 
            {
                $descuento = $cliente->getDescuento();
            } 
            else 
            {
                Logger::log( "No se tiene registro del cliente : " . $data->id_cliente );
                DAO::transRollback();
                die( '{"success": false, "reason": "No se tienen registros sobre el cliente ' . $data -> id_cliente . '" }' );
            }
        }
        catch(Exception $e)
        {
            DAO::transRollback();
            Logger::log($e);
            die( '{"success": false, "reason": "Intente de nuevo." }' );
        }

        $venta->setDescuento( $descuento );

        try{
            if (VentasDAO::save($venta)) 
        {
            $id_venta =  $venta->getIdVenta();
        } 
        else 
        {
            DAO::transRollback();
            die( '{"success": false, "reason": "No se pudo registrar la venta" }' );
        }
    }
    catch(Exception $e)
    {
        DAO::transRollback();
        Logger::log($e);
        die( '{"success": false, "reason": "Intente de nuevo." }' );
    }



    if($data->factura){
        nsertarFacturaVenta($venta);
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
		if( $p -> procesado == true ){		
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

    Logger::log("Venta {$id_venta} exitosa !");


}//venderAdmin


switch( $args['action'] ){

    case 100:
		//realizar una venta desde una sucursal
        vender($args);
    break;	

    case 101:
        //realizar una venta desde el admin
        venderAdmin( $args );
    break;
    
    case 102:
        var_dump( $_SESSION );
    break;


}

?>



