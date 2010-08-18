<?php

/**
 *	Controller de Prueba
 *
 *	Contiene todas las funciones que de mostrador
 *	@author Alan Gonzalez <alan@caffeina.mx>
 */


require_once('../server/model/model.inc.php');



function existeProd( $prodID )
{
	
	if($prodID == null){
		return;
	}
	
	$dI = DetalleInventarioDAO::getByPK( $prodID, $_SESSION['sucursal'] );
	
	if($dI == null){
		
	
		$criteria = new DetalleInventario();
		
		$criteria->setIdProducto( $prodID );
		
		$res = DetalleInventarioDAO::search($criteria);
		
		if(count($res) == 0){
			echo "{\"success\": false, \"reason\": \"Este productno existe.\"}";
			return;
		}
		
		$json = "{\"success\": false, \"reason\": \"<b>Este producto no existe en esta sucursal. Sin embargo existe en nuestras sucurales de :</b><br><br>";
		foreach($res as $p){
			$s = SucursalDAO::getByPK( $p->getIdSucursal() );
			$json .= $s->getDireccion() . "<br>";
		}
		$json .= "\"}";
		
		echo $json;
		
		return;
	}
	
	$inv = InventarioDAO::getByPK( $dI->getIdProducto() );
	
	$arr = array (
		'id_producto'=>  $dI->getIdProducto(), 
		'nombre'=> 		 $inv->getNombre(),
		'denominacion'=> $inv->getDenominacion(),
		'precio_venta'=> $dI->getPrecioVenta(),
		'existencias'=>  $dI->getExistencias() );

	echo json_encode($arr);
	

}
	
function listarClientes()
{
	// obtener todos los clientes cuyo id es mayor a uno,
	// ya que el uno es para la caja comun
	
	$crit = new Cliente();
	$crit->setIdCliente("1");
	
	$crit2 = new Cliente();
	$crit2->setIdCliente("9999");		
	
	$clientes = ClienteDAO::byRange( $crit, $crit2, true );
	
	echo $clientes;
}


function insertarVenta($cliente, $tipo_venta, $items)
{

	global $logger;
	$logger->setIdent("Mostrador");
		
	$stuff = json_decode($items);
	
	//obtener el usuario
	$usuario = UsuarioDAO::getByPK( $_SESSION['userid'] );
	
	//seleccionar un cliente
	if($cliente === 'caja_comun')
	{
		//el id de la caja comun es [el id de la sucursal] multiplicado por menos uno
		$cliente = ClienteDAO::getByPK( ((int)$_SESSION['sucursal']) * -1 );	

		if($cliente === null)
		{
			echo "{\"success\": false, \"reason\": \"No existe la caja comun para esta sucursal.\"}";
			return;
		}	
	}else
	{
		$cliente = ClienteDAO::getByPK( $cliente );
		if($cliente === null)
		{
			echo "{\"success\": false, \"reason\": \"No se ha podido seleccionar al cliente.\"}";
			return;
		}
	}


	//insertar venta 'vacia'
	$venta = new Ventas();
	$venta->setIdCliente( $cliente->getIdCliente() );
	$venta->setIdUsuario( $usuario->getIdUsuario() );
	$venta->setTipoVenta( $tipo_venta );
	$venta->setIdSucursal( $usuario->getIdSucursal() );
	$venta->setDescuento( $cliente->getDescuento() );
	$venta->setTotal( 0 );
	$venta->setPagado( 0 );
	$venta->setIP( $_SERVER['REMOTE_ADDR'] );
			
	try{
		$resultado = VentasDAO::save($venta);		
	}catch(Exception $e){
		echo "{\"success\": false, \"reason\": \"". addslashes($e) ."\"}";
		return;
	}
	
	//loop de items
	$array_items = json_decode($items);
	
	//merge items
	//si tengo en mi lista, 1 item con el id 14, y 1 item con el id 14, y 1 item con el id 3
	//convertir a 2 items del 14, y 1 del 3
	$new_array = array();
	foreach( $array_items as $item )
	{
		
		$found = false;
		foreach($new_array as $savedItem)
		{
			if( $item->id == $savedItem->id )
			{
				$found = true;
				$savedItem->cantidad += $item->cantidad;
				break;
			}
		}
		
		//si no lo encontre, salvalo		
		if(!$found)
			array_push($new_array, $item);
		
	}

	$array_items = $new_array;
	$subtotal  = 0;
	
	foreach( $array_items as $item )
	{
		//sacar la informacion de un nuevo objeto en la base

		$producto = DetalleInventarioDAO::getByPK( $item->id, $_SESSION['sucursal'] );
		
		if($producto === null){
			echo "{\"success\": false, \"reason\": \"el producto ".$item->id." que me haz enviado no existe.\"}";
			return;
		}
		
		//confirmar que no han estado manipulando la informacion
		if($producto->getPrecioVenta() != $item->cost )
		{
			//LOG THIS !
			$logger->log("Incongruencia de precios." );
			echo "{\"success\": false, \"reason\": \"Fuck you hacker.\"}";
			return;
		}
		
		//confirmar que existan tantos productos de lo que quiero comprar
		if( $item->cantidad > $producto->getExistencias() )
		{
			//LOG THIS !
			echo "{\"success\": false, \"reason\": \"Solamente hay " . $producto->getExistencias() .  " del producto ". $item->id . ".\"}";
			return;
		}

		$detalleVenta = new DetalleVenta();
		$detalleVenta->setIdVenta( $venta->getIdVenta() );
		$detalleVenta->setIdProducto( $producto->getIdProducto() );
		$detalleVenta->setCantidad( $item->cantidad );
		$detalleVenta->setPrecio( $producto->getPrecioVenta() );
		$subtotal += ( $item->cantidad * $producto->getPrecioVenta() );
		
		try{
			$res = DetalleVentaDAO::save($detalleVenta);
		}catch(Exception $e){
			echo "error" . $e;
			return;
		}
	}

	$imp = new Impuesto();
	$imp->setIdSucursal( $_SESSION['sucursal'] );
	$impuestos = ImpuestoDAO::search( $imp );
	
	//sumo los impuestos, ya que varios impouestos aplicacn a una misma sucursal	
	$totalImp = 0;
	foreach( $impuestos as $imp )
	{
		$totalImp += $imp->getValor();
	}
	
	
	$venta->setSubTotal( $subtotal );
	$venta->setIva( $totalImp );
	$total = __pos__calcularTotal($subtotal, $totalImp, $cliente->getDescuento());
	$venta->setTotal( $total );
	
	if($tipo_venta == 'contado')
	{
		$venta->setPagado( $total );
		$venta->setTotal( $total );
	}else{
		
		if($total > $cliente->getLimiteCredito()){
			echo "{\"success\": false, \"reason\":  \"Limite de Credito excedido.\"}";
			return;
		}
		
	}

	
	try{
		$res = VentasDAO::save( $venta );		
	}catch(Exception $e){
		echo "Error "  . $e;
		return;
	}

	
	//todo salio bien, descontar estos productos del inventario
	$criterio = new DetalleVenta();
	$criterio->setIdVenta( $venta->getIdVenta() );
	$res= DetalleVentaDAO::search($criterio);
	
	foreach($res as $dv)
	{
		$inventario = DetalleInventarioDAO::getByPK( $dv->getIdProducto(), $usuario->getIdSucursal() );
		$inventario->setExistencias( $inventario->getExistencias(  ) - $dv->getCantidad() ); 
		DetalleInventarioDAO::save( $inventario );
	}
	
	
	echo "{\"success\": true, \"v_id\":  ".$venta->getIdVenta()."}";
	
	$logger->log("Venta a " . $tipo_veta ." efecutada. Total: " . $total );


}


//obtener el iva que aplica a esta sucursal
function getIVA()
{
	$imp = new Impuesto();
	$imp->setIdSucursal( $_SESSION['sucursal'] );
	$impuestos = ImpuestoDAO::search( $imp );
	
	//sumo los impuestos, ya que varios impouestos aplicacn a una misma sucursal	
	$totalImp = 0;
	foreach( $impuestos as $imp )
	{
		$totalImp += $imp->getValor();
	}
	
	
	echo "{\"success\": true, \"iva\":  " . $totalImp . "}";
}



function facturarVenta($id_venta)
{
	
}


switch($args['action'])
{
	case 2101: existeProd( $args['id_producto'] ); break;
	case 2102: listarClientes(); break;
	case 2103: insertarVenta( $args['id_cliente'], $args['tipo_venta'], $args['jsonItems'] ); break;
	case 2104: getIva(); break;
	case 2105: facturarVenta( $args['id_venta'] ); break;
	default: echo "NOPE";
	
}