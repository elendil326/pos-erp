<?php 

require_once('model/cliente.dao.php');
require_once('model/ventas.dao.php');
require_once('model/pagos_venta.dao.php');
require_once('model/detalle_venta.dao.php');
require_once('model/factura_venta.dao.php');
require_once('model/sucursal.dao.php');
require_once('model/usuario.dao.php');

function crearCliente( $args ){

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

	//crear el objeto de cliente a ingresar
	$cliente = new Cliente();
	$cliente->setRfc( $data->rfc );
	
	//buscar que no exista ya un cliente con este RFC
	if( count(ClienteDAO::search( $cliente )) > 0)
    {
		die ( '{"success": false, "reason": "Ya existe un cliente con este RFC." }' );
	}

    $cliente->setNombre( $data->nombre );
	$cliente->setDireccion( $data->direccion );
	$cliente->setLimiteCredito( $data->limite_credito );
	$cliente->setDescuento( $data->descuento );
	$cliente->setTelefono( $data->telefono );
	$cliente->setEMail( $data->e_mail );
	
	$cliente->setActivo ( 1 );
	$cliente->setCiudad ( $data->ciudad );

	//si esta peticion viene de un administrador, usar los 
	// datos que vienen en el request, de lo contrario
	// utilizar los datos que estan en la sesion
	if($_SESSION[ 'grupo' ] <= 1)
    {
		$cliente->setIdSucursal ( $data->id_sucursal );
		$cliente->setIdUsuario ( $data->id_usuario );
	}
    else
    {
		$cliente->setIdSucursal ( $_SESSION['sucursal'] );
		$cliente->setIdUsuario ( $_SESSION['userid'] );
	}

	try{

	    if (ClienteDAO::save($cliente)) 
        {
	        printf('{"success": true, "id": "%s"}' , $cliente->getIdCliente());
	    } 
        else 
        {
	        die( '{"success": false, "reason": "Error" }' );
	    }
	}
    catch(Exception $e)
    {
	    die( '{"success": false, "reason": "Error" }' );
	}

}

function listarClientes(  ){
	$total_customers = array();
	
	//buscar clientes que esten activos
	$tcliente = new Cliente();
	$tcliente->setActivo(1);
	$clientes = ClienteDAO::search($tcliente);

	foreach ($clientes as $cliente)
    {
	    //si es una caja comun, continuar
		if( $cliente->getIdCliente() < 0 )
        {
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
			
		$c = json_decode($cliente, true);
		$c["credito_restante"] = $cliente->getLimiteCredito() - $por_pagar;
		
        array_push($total_customers, $c );
	}
	
	return $total_customers;

}

function modificarCliente( $args ){

	if( !isset($args['data']) )
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

	//crear el objeto de cliente a ingresar
	$cliente = ClienteDAO::getByPK ( $data->id_cliente );
	
	//buscar que no exista ya un cliente con este RFC
	if( !$cliente )
    {
		die ( '{"success": false, "reason": "Este cliente no existe." }' );
	}
	
	if( isset($data->rfc) )
		$cliente->setRfc( $data->rfc );
	
	if( isset($data->nombre) )
		$cliente->setNombre( $data->nombre );
		
	if( isset($data->direccion) )
		$cliente->setDireccion( $data->direccion );		

	if( isset($data->limite_credito) )	
		$cliente->setLimiteCredito( $data->limite_credito );
	
	if( isset($data->descuento) )		
		$cliente->setDescuento( $data->descuento );
	
	if( isset($data->telefono) )		
		$cliente->setTelefono( $data->telefono );
	
	if( isset($data->e_mail) )		
		$cliente->setEMail( $data->e_mail );
	
	if( isset($data->activo) )		
		$cliente->setActivo ( $data->activo );
	
	if( isset($data->ciudad) )		
		$cliente->setCiudad ( $data->ciudad );	

	//solo el admin puede editar estos campos
	if($_SESSION[ 'grupo' ] <= 1)
    {
		if( isset($data->id_sucursal) )	
			$cliente->setIdSucursal ( $data->id_sucursal );
			
		if( isset($data->id_usuario) )				
			$cliente->setIdUsuario ( $data->id_usuario );
	}

	try
    {

	    if (ClienteDAO::save($cliente)) 
        {
	        printf( '{"success": true, "id": "%s"}' , $cliente->getIdCliente() );
	    } else 
        {
	        die( '{"success": false, "reason": "Los datos son indenticos, debe de modificar almenos un campo." }' );
	    }
	
	}
    catch(Exception $e)
    {
	    die( '{"success": false, "reason": "'.$e.'" }' );
	}
	
}


function listarVentasClientes( ){
    
    $ventas = VentasDAOBase::getAll();

    $tot_ventas = array();

    foreach($ventas as $venta)
    {

        $decode_venta = json_decode($venta);
        
        //agregamos al venta al total de ventas

        $dventa = new DetalleVenta();
        $dventa->setIdVenta( $venta->getIdVenta() );
        //obtenemos el detalle de la venta
        $detalles_venta = DetalleVentaDAO::search($dventa);

        $array_detalle = array(); //guarda los detalles de las ventas

        foreach($detalles_venta as $detalle_venta)
        {
            $detalle = json_decode($detalle_venta);
            array_push($array_detalle,$detalle); //inserta los detalles de las ventas
        } 
        
        $decode_venta->{"detalle_venta"} = $array_detalle;
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
			"total" => $c->getTotal()
		);
		

		
		array_push( $cC, $data );
	}

	return $cC;

}




function abonarCompra( $args ){

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

    if(!isset($data->id_venta) )
    {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    if(!isset($data->monto) )
    {
        die('{"success": false, "reason": "Parametros invalidos." }');
    }

    $pagosVenta = new PagosVenta();
    $pagosVenta->setIdVenta( $data->id_venta );
    $pagosVenta->setIdSucursal( $_SESSION['sucursal'] );
    $pagosVenta->setIdUsuario(  $_SESSION['userid'] );
    $pagosVenta->setMonto( $data->monto );

    try
    {
        //ingresamos el pago a la BD
        if( PagosVentaDAO::save($pagosVenta) < 1)
        {
            echo '{ "success":"false", "reason":"Error" }';
        }
    }
    catch(Exception $e)
    {
        die( '{"success": false, "reason": "'.$e.'" }' );
    }

    //ya que se ingreso modificamos lo pagado a al venta
    $venta = VentasDAOBase::getByPK( $data->id_venta );
    $venta->setPagado( $venta->getPagado() +  $data->monto );
    
    if(VentasDAOBase::save($venta) == 1)
    {
        echo '{"success":"true"}';
    }
    else
    {
        echo '{ "success":"false", "reason":"No actualizo el pago a la venta." }';
    }
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

        $c = json_decode($cliente, true);

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
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
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
        die('{"success": false, "reason": "No hay parametros para ingresar." }');
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
 * Lista los abonos de un cliente especifico
 * */
function listarAbonos( $cid )
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
			printf('{ "success": true, "datos": %s }',  json_encode(listarClientes(  ) ));
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

	    case 303:
	        //lista las ventas de un cliente en especidico (puede ser de contado o a credito si se especifica)
	    	printf('{ "success": true, "datos": %s }',  json_encode( listarVentasCliente( $args['id_cliente'], $args['tipo_venta']) ));
	    break;

	    case 304:
	        //lista todas las ventas
	    	printf('{ "success": true, "datos": %s }',  json_encode( listarVentasClientes() ));
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
