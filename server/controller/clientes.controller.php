<?php 
/** Clientes Controller.
  * 
  * Este archivo es la capa entre la interfaz de usuario y peticiones ajaxa y los 
  * procedimientos para realizar las operaciones sobre Clientes. 
  * @author Alan Gonzalez <alan@caffeina.mx>, Manuel Garcia Carmona <manuel@caffeina.mx>
  * 
  */
require_once('model/cliente.dao.php');
require_once('model/ventas.dao.php');
require_once('model/inventario.dao.php');
require_once('model/pagos_venta.dao.php');
require_once('model/detalle_venta.dao.php');
require_once('model/factura_venta.dao.php');
require_once('model/sucursal.dao.php');
require_once('model/usuario.dao.php');
require_once('logger.php');






/**
  *	Crea un cliente. 
  *	
  * Este metodo intentara crear un cliente dado un arreglo de datos proporcionado.
  *	
  *	@static
  * @throws Exception si la operacion fallo.
  * @param Autorizacion [$autorizacion] El objeto de tipo Autorizacion
  * @return Un entero mayor o igual a cero denotando las filas afectadas, o un string con el error si es que hubo alguno.
  **/
function crearCliente( $args ){

	if(!isset($args['data']))
    {
        Logger::log("No hay parametros para ingresar nuevo cliente.");
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}	

	$data = parseJSON( $args['data'] );

	if($data == null){
		Logger::log("Json invalido para crear cliente:" . $args['data']);
		die('{ "success": false, "reason" : "Parametros invalidos" }');
	}
	
	
	if(!( isset($data->rfc) &&
			isset($data->nombre) &&
			isset($data->direccion) &&
			isset($data->descuento) &&
			isset($data->telefono) &&
			isset($data->ciudad) &&
			isset($data->limite_credito)
		)){
		Logger::log("Faltan parametros para crear cliente:" . $args['data']);
		die('{ "success": false, "reason" : "Faltan parametros." }');
	}

	//crear el objeto de cliente a ingresar
	$cliente = new Cliente();
	$cliente->setRfc( $data->rfc );
	
	//buscar que no exista ya un cliente con este RFC
	if( count(ClienteDAO::search( $cliente )) > 0){
		Logger::log("RFC ya existe en clientes.");
		die ( '{"success": false, "reason": "Ya existe un cliente con este RFC." }' );
	}



	if(strlen($data->nombre) < 10){
		Logger::log("Nombre muy corto para insertar cliente.");
		die ( '{"success": false, "reason": "El nombre del cliente es muy corto." }' );		
	}

	//un cliente puede no tener telefono
	/*
	if(strlen($data->telefono) < 5){
		Logger::log("Telefono muy corto para insertar cliente.");
		die ( '{"success": false, "reason": "El telefono del cliente es muy corto." }' );		
	}*/

	if($data->descuento < 0){
		Logger::log("Descuento negativo para el cliente.");
		die ( '{"success": false, "reason": "El descuento del cliente no puede ser negativo." }' );		
	}

	if($data->descuento > POS_MAX_LIMITE_DESCUENTO){
		Logger::log("Descuento mayor a ".POS_MAX_LIMITE_DESCUENTO." para el cliente.");
		die ( '{"success": false, "reason": "El descuento del cliente no puede ser tan grande." }' );		
	}

	if(!is_numeric($data->limite_credito)){
		die ( '{"success": false, "reason": "El Limite de credito debe ser un numero." }' );			
	}
	
	

	if($data->limite_credito < 0){
		Logger::log("Limite de credito negativo para el cliente.");
		die ( '{"success": false, "reason": "El Limite de credito del cliente no puede ser negativo." }' );		
	}

	if($data->limite_credito > POS_MAX_LIMITE_DE_CREDITO){
		Logger::log("Descuento mayor a ".POS_MAX_LIMITE_DESCUENTO." para el cliente.");
		die ( '{"success": false, "reason": "El Limite de credito del cliente no puede ser tan grande." }' );		
	}

    $cliente->setNombre( $data->nombre );
	$cliente->setDireccion( $data->direccion );
	$cliente->setLimiteCredito( $data->limite_credito );
	$cliente->setDescuento( $data->descuento );
	$cliente->setTelefono( $data->telefono );
	$cliente->setCiudad ( $data->ciudad );
		
	if(isset($data->e_mail))
		$cliente->setEMail( $data->e_mail );
	
	$cliente->setActivo ( 1 );

	//si esta peticion viene de un administrador, usar los 
	// datos que vienen en el request, de lo contrario
	// utilizar los datos que estan en la sesion
	if($_SESSION[ 'grupo' ] <= 1)
    {
    	if( isset($data->id_sucursal) && isset($data->id_usuario) ){
			$cliente->setIdSucursal ( $data->id_sucursal );
			$cliente->setIdUsuario ( $data->id_usuario );    	
    	}else{
    		die('{"success": false, "reason": "Debe proporcionar una sucursal y un usuario." }');
    	}

		
	}else{
		$cliente->setIdSucursal ( $_SESSION['sucursal'] );
		$cliente->setIdUsuario ( $_SESSION['userid'] );
	}

	try{
		ClienteDAO::save($cliente);
		printf('{"success": true, "id": "%s"}' , $cliente->getIdCliente());
	}catch(Exception $e){
        Logger::log("Error al guardar el nuevo cliente:" . $e);
	    die( '{"success": false, "reason": "Error" }' );
	}

}










function listarClientes(  ){
	$total_customers = array();
	
	//buscar clientes que esten activos
	$foo = new Cliente();
	$foo->setIdCliente("0");
	$foo->setActivo("1");


	$bar = new Cliente();
	$bar->setIdCliente("9999");

	$clientes = ClienteDAO::byRange($foo, $bar);

	foreach ($clientes as $cliente)
    {
			
		//buscar a este cliente en las ventas a credito
		$qventa = new Ventas();
		$qventa->setIdCliente( $cliente->getIdCliente() );
		$qventa->setTipoVenta( "credito" );
		$res = VentasDAO::search($qventa);
			
		$por_pagar = 0;
		foreach($res as $venta)
        {
			//restar lo que ha pagado del total
			$por_pagar += $venta->getTotal() - $venta->getPagado();
		}
			

        $c = $cliente->asArray();
		$c["credito_restante"] = $cliente->getLimiteCredito() - $por_pagar;
		
        array_push($total_customers, $c );
	}
	
	return $total_customers;

}








function modificarCliente( $args ){

	if( !isset($args['data']) ){
        Logger::log("No hay parametros para editar cliente.");
		die('{"success": false, "reason": "Parametros invalidos." }');
	}
	
	
	$data = parseJSON( $args['data'] );	

	if($data == null){
        Logger::log("Json invalido para modificar cliente: " . $args['data']);
		die('{"success": false, "reason": "Parametros invalidos." }');	
	}

	//minimo debio haber mandado el id_cliente
	if(!isset($data->id_cliente)){
		Logger::log("Json invalido para modificar cliente: " . $args['data']);
		die('{"success": false, "reason": "Parametros invalidos." }');	
	}

	//crear el objeto de cliente a ingresar
	$cliente = ClienteDAO::getByPK ( $data->id_cliente );

	
	if( !$cliente ){
        Logger::log("No existe el cliente " . $data->id_cliente);
		die ( '{"success": false, "reason": "Este cliente no existe." }' );
	}
	
	if( isset($data->rfc) )
		$cliente->setRfc( $data->rfc );
	
	if( isset($data->nombre) )
		$cliente->setNombre( $data->nombre );
		
	if( isset($data->direccion) )
		$cliente->setDireccion( $data->direccion );		


	if( isset($data->limite_credito) ){
        //validar limite de credito
        
        if( $data->limite_credito < 0 ){
            Logger::log("Intentando ingresar limite de credito negativo");
    		die ( '{"success": false, "reason": "El limite de credito no puede ser negativo." }' );
        }

        if( $data->limite_credito >= POS_MAX_LIMITE_DE_CREDITO && $_SESSION['grupo'] == 2 ){

            Logger::log("gerente intentando asignar limite de credito mayor a " . POS_MAX_LIMITE_DE_CREDITO);

            $max = POS_MAX_LIMITE_DE_CREDITO;
    		die ( '{"success": false, "reason": "Si desea asignar un limite de credito mayor a ' . $max . ' debera pedir una autorizacion."  }' );
        }

		$cliente->setLimiteCredito( $data->limite_credito );
    }

	
	if( isset($data->descuento) ){

        if( $data->descuento > POS_MAX_LIMITE_DESCUENTO ){
            $max = POS_MAX_LIMITE_DESCUENTO;
            Logger::log("intentando asignar descuento mayor a " . $max);
       		die ( '{"success": false, "reason": "No se puede asignar un descuento mayor al ' . $max . '%."  }' );
        }

		$cliente->setDescuento( $data->descuento );
    }	

	
	if( isset($data->telefono) )		
		$cliente->setTelefono( $data->telefono );
	
	if( isset($data->e_mail) )		
		$cliente->setEMail( $data->e_mail );
	
	if( isset($data->activo) )		
		$cliente->setActivo ( $data->activo );
	
	if( isset($data->ciudad) )		
		$cliente->setCiudad ( $data->ciudad );	


	//solo el admin puede editar estos campos
	if($_SESSION[ 'grupo' ] <= 1){
		if( isset($data->id_sucursal) )	
			$cliente->setIdSucursal ( $data->id_sucursal );
			
		if( isset($data->id_usuario) )				
			$cliente->setIdUsuario ( $data->id_usuario );
	}

	try{
       ClienteDAO::save($cliente);
       printf( '{"success": true, "id": "%s"}' , $cliente->getIdCliente() );
       Logger::log("Cliente " . $cliente->getIdCliente() . " modificado !");

	} catch(Exception $e) {
	
        Logger::log("Error al guardar modificacion del cliente " . $e);
	    die( '{"success": false, "reason": "Error. Porfavor intente de nuevo." }' );
	}
	
}









function listarVentasClientes( ){
    
    $ventas = VentasDAO::getAll();
    $tot_ventas = array();

    foreach($ventas as $venta)
    {

        $decode_venta = $venta->asArray();

        $dventa = new DetalleVenta();
        $dventa->setIdVenta( $venta->getIdVenta() );
        
        //obtenemos el detalle de la venta
        $detalles_venta = DetalleVentaDAO::search($dventa);
        
        $array_detalle = array(); //guarda los detalles de las ventas

        foreach($detalles_venta as $detalle_venta)
        {
            $detalle = parseJSON( $detalle_venta );
            
            $descripcion = InventarioDAO::getByPK( $detalle_venta->getIdProducto() );
            
            $detalle['descripcion'] = $descripcion->getDescripcion();
            
            array_push($array_detalle, $detalle);
        } 
        
        $decode_venta["detalle_venta"] = $array_detalle;

        $suc = SucursalDAO::getByPK( $venta->getIdSucursal() );
        $decode_venta['sucursal'] = $suc->getDescripcion();

        $cajero = UsuarioDAO::getByPK( $venta->getIdUsuario() );
        $decode_venta['cajero'] = $cajero->getNombre();
        
        array_push( $tot_ventas, $decode_venta );
    }

	return $tot_ventas;
    
}









//lista las ventas de un cliente en especidico (puede ser de contado o a credito si se especifica)
function listarVentaCliente( $id_cliente, $tipo_venta = null ){
    

    if(!isset($id_cliente)){
        return null;
    }

	$cC = array();
  
    $ventas = new Ventas();
    $ventas->setIdCliente($id_cliente);
    
    if(isset($tipo_venta))
    {
        $ventas->setTipoVenta($tipo_venta);
    }

    $comprasCliente = VentasDAO::search($ventas);


	foreach( $comprasCliente as $c )
	{
		//make readable data
	
		$sucursal = SucursalDAO::getByPK( $c->getIdSucursal() );
		$cajero = UsuarioDAO::getByPK( $c->getIdUsuario() );
		
		$data = array(
			"cajero" => $cajero ? $cajero->getNombre() : "<b>Error</b>",
			"sucursal" => $sucursal ? $sucursal->getDescripcion() : "<b>Error</b>",
		
			"descuento" => $c->getDescuento(),
			"fecha" => $c->getFecha(),
			"id_cliente" => $c->getIdCliente(),
			"id_sucursal" => $c->getIdSucursal(),
			"id_usuario" => $c->getIdUsuario(),
			"id_venta" => $c->getIdVenta(),
			"ip" => $c->getIp(),
			"iva" => $c->getIva(),
			"pagado" => $c->getPagado(),
			"subtotal" => $c->getSubtotal(),
			"tipo_venta" => $c->getTipoVenta(),
			"total" => $c->getTotal(),
            "saldo" => $c->getTotal() - $c->getPagado()
		);
		

		
		array_push( $cC, $data );
	}

	return $cC;

}










/*
 *  @TODO: Estea debe llamarse abonarAVenta !!
 *
 **/
function abonarCompra( $args ){

    if(!isset($args['data']))
    {
        Logger::log("No hay parametros para abonar a la compra");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

	$data = parseJSON( $args['data'] );


    if(!isset($data->id_venta) ){
        Logger::log("No se envio un id_venta para abonar");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    if(!isset($data->monto) ){
        Logger::log("No se envio un monto para abonar");
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

	if(!is_numeric($data->monto )){
        Logger::log("El monto a abonar debe ser un numero");
        die('{"success": false, "reason": "Parametros invalidos." }');		
	}


	if( $data->monto < 0){
        Logger::log("El monto a abonar no puede ser negativo");
        die('{"success": false, "reason": "No puede abonar un monto negativo." }');
	}
	

	if($_SESSION['grupo'] <= 1){
	 	if(!( isset($data->sucursal) && isset($data->userid) )){
	 		die('{"success": false, "reason": "Faltan parametros." }');
	 	}
	 	// TODO:validar esta sucursal y este usuario
	 	$sid = $data->sucursal;
	 	$uid = $data->userid;
	}else{
		$sid = $_SESSION['sucursal'];
		$uid = $_SESSION['userid'];
	}

    $pagosVenta = new PagosVenta();
    $pagosVenta->setIdVenta( $data->id_venta );
    $pagosVenta->setIdSucursal( $sid );
    $pagosVenta->setIdUsuario ( $uid );
    $pagosVenta->setMonto( $data->monto );

	DAO::transBegin();

    try{
    
		PagosVentaDAO::save($pagosVenta);
    	//ya que se ingreso modificamos lo pagado a al venta
    	$venta = VentasDAOBase::getByPK( $data->id_venta );
	    $venta->setPagado( $venta->getPagado() +  $data->monto );
		VentasDAOBase::save($venta);
	    
    }catch(Exception $e){
        Logger::log("Error al intentar guardar el abono "  . $e);
        DAO::transRollback();
        die( '{"success": false, "reason": "Error, porfavor intente de nuevo." }' );
    }

   
	DAO::transEnd();
    Logger::log("Abono exitoso a la venta " . $data->id_venta);
}










function listarClientesDeudores(  ){
    $total_customers = array();
    
    //buscar clientes que esten activos
    $tcliente = new Cliente();
    $tcliente->setActivo(1);
    $clientes = ClienteDAO::search($tcliente);

    foreach ($clientes as $cliente)
    {
        //si es una caja comun, continuar
        if( $cliente->getIdCliente() < 0 ){
            continue;
        }

        //buscar a este cliente en las ventas a credito
        $qventa = new Ventas();
        $qventa->setIdCliente( $cliente->getIdCliente() );
        $qventa->setTipoVenta( "credito" );             
        $res = VentasDAO::search($qventa);

        $por_pagar = 0;
        foreach($res as $venta)
        {
            //restar lo que ha pagado del total
            $por_pagar += $venta->getTotal() - $venta->getPagado();
        }

        $c = $cliente->asArray();
        

        $c["credito_restante"] = $cliente->getLimiteCredito() - $por_pagar;
        $c["saldo"] = $por_pagar;	

		if($por_pagar > 0){
        	array_push($total_customers, $c );			
		}

    }

	return $total_customers;

}












function facturarVenta( $args ){

    if( !isset($args['id_venta']) )
    {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $venta = VentasDAOBase::getByPK($args['id_venta']);

    //verificamos que la venta exista
    if($venta == null)
    {
        die( '{"success": false, "reason": "No se encontro registro de la venta ' . $args['id_venta'] . '." }' );
    }

    if( $venta->getPagado() != $venta->getTotal() )
    {
        die( '{"success": false, "reason": "Esta venta no esta liquidada." }' );
    }

    $factura = new FacturaVenta();
    
    $factura->setIdVenta($args['id_venta']);

    try{

        if (FacturaVentaDAOBase::save($factura)) 
        {
            echo sprintf( '{"success": "true"}' );
        } 
        else 
        {
             die( '{"success": false, "reason": "No se pudo crear la factura." }' );
        }
    
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "'.$e.'" }' );
    }

}











function imprimirSaldo( $args ){

    if( !isset($args['id_venta']) )
    {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $venta = VentasDAOBase::getByPK($args['id_venta']);

    //verificamos que la venta exista
    if( $venta == null )
    {
        die( '{"success": false, "reason": "No se encontro registro de la venta ' . $args['id_venta'] . '." }' );
    }

    $saldo = $venta->getTotal() - $venta->getPagado();
    {
       printf('{ "success": true, "saldo": %s }',  json_encode($saldo));
    }

}













/*
 * Lista los abonos de un cliente especifico y de ser necesario una venta especifica
 * */
function listarAbonos( $cid, $vid = null )
{

	$abonos = array();

    $ventas = new Ventas();
    $ventas->setIdCliente($cid);
    $ventas->setTipoVenta('credito');
    $comprasCliente = VentasDAO::search($ventas);

	foreach( $comprasCliente as $venta )
	{
		
		$pago = new PagosVenta();
		$pago->setIdVenta( $venta->getIdVenta() );
		
		$pagosVenta = PagosVentaDAO::search( $pago );
		
		foreach( $pagosVenta as $p )
		{
			//make readable data
		
			$sucursal = SucursalDAO::getByPK( $p->getIdSucursal() );
			$cajero = UsuarioDAO::getByPK( $p->getIdUsuario() );
			
            
            if($vid != null && $vid != $p->getIdVenta())continue;

			$data = array(
				"cajero" => $cajero ? $cajero->getNombre() : "<b>Error</b>",
				"sucursal" => $sucursal ? $sucursal->getDescripcion() : "<b>Error</b>",
				"fecha" => $p->getFecha(),
				"id_pago" => $p->getIdPago(),
				"id_sucursal" => $p->getIdSucursal(),
				"id_usuario" => $p->getIdUsuario(),
				"id_venta" => $p->getIdVenta(),
				"monto" => $p->getMonto()
			);
			

			
			array_push( $abonos, $data );
		}
		
	}

	return $abonos;
}

/*
 * 
 * 	Case dispatching for proxy
 * 
 * */
if(isset($args['action'])){
	switch($args['action'])
	{
		case 300:
	        //lista todos los clientes
            $json = json_encode( listarClientes() );
            
            if(isset($args['hashCheck'])){
                //revisar hashes
                if(md5( $json ) == $args['hashCheck'] ){
                    return;
                }
            }

	    	printf('{ "success": true, "hash" : "%s" , "datos": %s }',  md5($json), $json );
		break;

		case 301:
	        //crea un nuevo cliente
			if($_SESSION['grupo'] > 2)
	        {
				die( '{ "success": false, "reason": "No tiene privilegios para hacer esto." }' ) ;
			}

			crearCliente( $args );
		break;

		case 302:
	        //edita un cliente
			if($_SESSION['grupo'] > 2)
	        {
				die( '{ "success": false, "reason": "No tiene privilegios para hacer esto." }' ) ;
			}
			modificarCliente( $args );
		break;

		/*
	    case 303:
	        //lista las ventas de un cliente en especidico (puede ser de contado o a credito si se especifica)
	    	printf('{ "success": true, "datos": %s }',  json_encode( listarVentasCliente( $args['id_cliente'], $args['tipo_venta']) ));
	    break;
	    */

	    case 304:
	        //lista todas las ventas
            $json = json_encode( listarVentasClientes() );
            
            if(isset($args['hashCheck'])){
                //revisar hashes
                if(md5( $json ) == $args['hashCheck'] ){
                    return;
                }
            }

	    	printf('{ "success": true, "hash" : "%s" , "datos": %s }',  md5($json), $json );
	    break;

	    case 305:
	        //agrega un pago a una venta
	        abonarCompra( $args );
	    break;

	    case 306:
	        //clientes deudores
	    	printf('{ "success": true, "datos": [%s] }',  json_encode( listarClientesDeudores(  ) ));
	    break;

	    case 307:
	        //factura una venta
	        facturarVenta( $args );
	    break;

	    case 308:
	        //imprime el saldo de una venta a credito
	        imprimirSaldo( $args );
	    break;

	}
	
}
