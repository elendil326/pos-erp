<?php
/** Clientes Ventas Controller.
  * 
  * Este script contiene las funciones necesarias para realizar las diferentes operaciones 
  * que se pueden hacer con respecto a las ventas hechas a un cliente, ver ventas, agregar productos a venta
  * y eliminar productos de venta entre otras.
  * @author Juan Manuel Hernadez <manuel@caffeina.mx> 
  * 
  */

/**
 * Se importan los DAO para poder realizar las operaciones sobre la BD.
 */ 
require_once('../server/model/ventas.dao.php');
require_once('../server/model/detalle_venta.dao.php');
require_once('../server/model/detalle_inventario.dao.php');
require_once('../server/model/pagos_venta.dao.php');
require_once('../server/model/productos_proveedor.dao.php');
require_once('../server/model/inventario.dao.php');
require_once('../server/model/impuesto.dao.php');
require_once('../server/model/sucursal.dao.php');
require_once('../server/model/usuario.dao.php');
require_once('../server/model/cliente.dao.php');
require_once('../server/model/factura_venta.dao.php');

/**
 * update_saleHeader
 *
 * Actualiza la tabla ventas con el subtotal e IVA correspondiente de una
 * venta existente. Se usa cuando se inserta o elimina un producto de una venta
 * asi como cuando se hacen devoluciones de producto. 
 * En caso que el cliente tenga descuento aplica el mismo sobre el subtotal de la venta
 *
 * @param <type> $id_venta
 * @param <type> $id_cliente
 * @param <type> $tipo_venta
 * @param <type> $subtotal
 */
function update_saleHeader($id_venta, $id_cliente, $tipo_venta, $subtotal ) { 
	$sucursal=$_SESSION['sucursal'];
	$id_usuario=$_SESSION['userid'];
	$sucursal=2;
	$id_usuario=1;
	
	$desc = 0;
	$cliente = ClienteDAO::getByPK( $id_cliente );
	if( $cliente->getDescuento() > 0 )
		$desc =  $subtotal * ( $cliente->getDescuento() / 100 ) ;
	
	$valorVenta = $subtotal - $desc;
	
	$impuesto = new Impuesto();
	$impuesto->setIdSucursal( $sucursal );
	$impuestos = ImpuestoDAO::search( $impuesto );//en mi bd el iva es el id 1
	
	$recargos = 0;
	if( count($impuestos) > 0 ){
		foreach( $impuestos as $imp){
			$recargos += ( $imp->getValor() / 100 ) * $valorVenta;
		}
	}
	
	$ventas = new Ventas();
	
	$ventas->setIdVenta($id_venta);
	$ventas->setIdCliente($id_cliente);
	$ventas->setTipoVenta($tipo_venta);
	$ventas->setSubtotal($valorVenta);
	$ventas->setIva( $recargos );
	$ventas->setIdSucursal($sucursal);
	$ventas->setIdUsuario($id_usuario);

	$res = VentasDAO::save($ventas);//regresa false o un int
	
	return $res;
}

/**
 * delete_sale
 *
 * Elimina una venta existente hecha a un cliente (como si nunca se hubiera hecho, se registro mal)
 * no se actualiza el almacen pero si se eliminan los pagos a esta venta si es que hay
 *
 * @param <type> $id_venta
 */
function delete_sale($id_venta) { 

	$detalles_venta = new DetalleVenta();
	$detalles_venta->setIdVenta( $id_venta );
	$ventas_borrar = DetalleVentaDAO::search( $detalles_venta );
	
	$subtotal = 0;
	
	foreach( $ventas_borrar as $detalle ){
		DetalleVentaDAO::delete( $detalle );
	}
	
	$venta = new Ventas();
	$venta->setIdVenta( $id_venta );
	
	if( $venta->getTipoVenta() == 'credito' ){
		$pagos = new PagosVenta();
		$pagos->setIdVenta( $id_venta );
		
		$pagos_venta = PagosVentaDAO::search( $pagos );
		
		
		if( count($pagos_venta) > 0 ){
			foreach( $pagos_venta as $pago){
				$subtotal += $pago->getMonto();
				PagosVentaDAO::delete( $pago );
			}
		}
	}else{
		$subtotal = $venta->getSubtotal();
	}
	
	$res = VentasDAO::delete($venta);
   
   if($res > 0 ){
	   return "{success: true , reason: 'Se elimino la venta satisfactoriamente, se pago a esta venta un total de: $ ".$subtotal."'}";
   }else{
	   return "{success: false , reason: 'No se pudo eliminar la venta'}";
   }
}//fin delete_sale

/**
 * entire_devolution_sale
 *
 * Elimina una venta existente hecha a un cliente y se regresan los productos al 
 * almacen (devolucion completa) asi como eliminar los pagos hechos a esta venta
 *
 * @param <type> $id_venta
 */
function entire_devolution_sale($id_venta) { 

	$detalles_venta = new DetalleVenta();
	$detalles_venta->setIdVenta( $id_venta );
	$ventas_borrar = DetalleVentaDAO::search( $detalles_venta );
	
	$venta_delete = VentasDAO::getByPK( $id_venta );
	$suc = $venta_delete->getIdSucursal();
	$subtotal = 0;
	
	foreach( $ventas_borrar as $detalle ){
		
		$detalle_inventario = DetalleInventarioDAO::getByPK( $detalle->getIdProducto(), $suc );
		$existencias = $detalle_inventario->getExistencias();
		$detalle_inventario->setExistencias( ( $existencias + $detalle->getCantidad() ) );
		
		DetalleVentaDAO::delete( $detalle );
	}
	
	if( $venta_delete->getTipoVenta() == 'credito' ){
		$pagos = new PagosVenta();
		$pagos->setIdVenta( $id_venta );
		
		$pagos_venta = PagosVentaDAO::search( $pagos );
		
		if( count($pagos_venta) > 0 ){
			foreach( $pagos_venta as $pago){
				$subtotal += $pago->getMonto();
				PagosVentaDAO::delete( $pago );
			}
		}
	}else{
		$subtotal = $venta_delete->getSubtotal();
	}
	$res = VentasDAO::delete($venta_delete);
   
   if($res > 0){
	   return "{success: true , reason: 'Se elimino la venta satisfactoriamente, se pago a esta venta un total de: $ ".$subtotal."'}";
   }else{
	   return "{success: false , reason: 'No se pudo eliminar la venta'}";
   }
}//fin entire_devolution_sale


/**
 * addItems_Existent_sale
 *
 * Funcion que inserta un nuevo producto a una venta existente, 
 * modifica el inventario de la sucursal y calcula el iva correspondiente 
 * de la venta de acuerdo al valor del impuesto establecido en la BD.
 *
 * @param <type> $id_venta
 * @param <type> $jsonItems
 */
function addItems_Existent_sale( $id_venta, $jsonItems, $id_cliente, $tipo_venta ) {
	$sucursal= $_SESSION['sucursal'];
	//$sucursal = 2;
	$arregloItems = json_decode($jsonItems,true);
	$dim = count($arregloItems);
	$out = "";
    
	
	$subtotalCompra =0;

		for($i=0; $i < $dim; $i++){
				
			$cantidad = $arregloItems[$i]['cantidad'];
			
			$inventario_detalle = DetalleInventarioDAO::getByPK( $arregloItems[$i]['id'], $sucursal );

			$precioP = $inventario_detalle->getPrecioVenta();
				
			$detalle_venta = new DetalleVenta();
				
			$detalle_venta->setIdVenta($id_venta);
			$detalle_venta->setIdProducto($arregloItems[$i]['id']);
			$detalle_venta->setCantidad($cantidad);
			$detalle_venta->setPrecio($precioP);
							
			$resul = DetalleVentaDAO::save($detalle_venta);
				
			if( $resul > 0 ){
					
				$detalle_inventario = DetalleInventarioDAO::getByPK( $arregloItems[$i]['id'], $sucursal );
				$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() - $cantidad);
				$res = DetalleInventarioDAO::save($detalle_inventario);
					
				if( $res < 1 ){
					$out .=" - No se agrego al inventario el producto con id: ".$arregloItems[$i]['id'];
				}
			}else{//fin resul
				$out .= " - No se inserto el producto con id: ".$arregloItems[$i]['id'];
			}
		
		}//fin for
		
		$venta = new DetalleVenta();
		$venta->setIdVenta($id_venta);
		$detallesVenta = DetalleVentaDAO::search($venta);
		
		$subtotal = 0;
		
		foreach($detallesVenta as $detalle){
			$subtotal += $detalle->getCantidad() * $detalle->getPrecio();
		}//foreach
		
		$actCabeceraVenta = update_saleHeader($id_venta, $id_cliente, $tipo_venta, $subtotal);
		
		if ( $actCabeceraVenta < 1 ){
			return sprintf ("{success: false , reason: 'No se modifico el subtotal e iva de la venta', details: '%s'}",$out);
		}else{		
			return sprintf ("{ success : true , reason :'Venta modificada completamente' , details:'%s'}",$out);
		}
	
	
}//fin addItem_purchase

/**
 * removeItem_Existent_sale
 *
 * Funcion que elimina un producto que esta en una venta existente, 
 * modifica el inventario de la sucursal (devolucion) y calcula el subtotal e iva correspondiente 
 * de la venta de acuerdo al valor del impuesto establecido en la BD.
 * En dado caso que la eliminacion de ese producto de esa venta implique
 * que haya devolucion de dinero dela sucursal hacia el cliente debido
 * a que fue de contado o los abonos no coinciden con el subtotal de la venta (se le debe dinero),
 * la funcion regulariza los pagos de la venta y arroja la cantidad a devolver en un mensaje
 * en el json dentro de la propiedad reason.
 *
 * @param <type> $jsonItems
 */
function removeItem_Existent_sale($id_venta , $id_producto) {
	
	$subtotal = 0;
    
	$detalle_venta= DetalleVentaDAO::getByPK( $id_venta, $id_producto );
	
	$cantidad = $detalle_venta->getCantidad();

	$ans = DetalleVentaDAO::delete($detalle_venta);
	
	if( $ans > 0 ){
		
		$venta = VentasDAO::getByPK($id_venta);
		$beforeSubtot = $venta->getSubtotal();//para comparar con lo que ha abonado
		$tipoCompra = $venta->getTipoVenta();
		
		$venta_detalle = new DetalleVenta();
		$venta_detalle->setIdVenta( $id_venta );
		
		$detallesVenta = DetalleVentaDAO::search( $venta_detalle );
		
		foreach($detallesVenta as $detalle){
			$subtotal += $detalle->getCantidad() * $detalle->getPrecio();
		}//foreach
		
		$sucursal=$venta->getIdSucursal();
		
		$detalle_inventario = DetalleInventarioDAO::getByPK( $id_producto , $sucursal );
		$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() + $cantidad );//devolucion
		$res = DetalleInventarioDAO::save($detalle_inventario);
		
		if( $res > 0 ){
			$actCabeceraCompra = update_saleHeader($id_venta, $venta->getIdCliente(), $venta->getTipoVenta(), $subtotal);
				
			if ( $actCabeceraCompra < 1 ){
				return "{success: false , reason: 'No se modifico el subtotal e iva de la compra'}";
			}
				
			$abonado = 0;
			$devolver = 0; 
			$out = "";
				
			$pagos_venta = PagosVenta();
			$pagos_venta->setIdVenta($id_venta);
				
			$pagos = PagosVentaDAO::search( $pagos_venta );
			
			if( count($pagos) > 0 ){
				
				foreach($pagos as $pago){
					$abonado += $pago->getMonto();
				}//fin for
				
				if( $abonado > $subtotal ){
					
					$devolver = $abonado - $subtotal;
					$result = payments_regularization($pagos , $subtotal ,$id_venta );
					
					if( $result > 0 ){
						$out .=', se le adeuda al cliente la cantidad de: $ '.$devolver;
					}else{
						$out .=', No se regularizaron los pagos de esta venta, al cliente se le adeuda: $'.$devolver;
					}
				}
			}//fin se regularizan pagos
			
			if( $tipoCompra == 'contado' ){
				$devolver = $beforeSubtot - $subtotal;
				$out .=', se le adeuda al cliente la cantidad de: $ '.$devolver;
			}
				
				
			return sprintf ("{success : true , reason :'Producto eliminado completamente de la venta %s'}",$out);
			
			
		}else{//else res
			return "{success: false , reason:'No se Modifico el inventario tras la eliminacion de este producto'}";
		}
	}else{//else ans
		return "{success: false, reason: 'No se elimino el producto de la venta' }";
	}
	
}//fin removeItem_Existent_sale

/**
 * payments_regularization
 *
 * Funcion que regulariza los pagos de una compra dada con respecto al nuevo subtotal. 
 * Se ejecuta solamente si la compra fue de contado o los abonos exceden el subtotal.
 * 
 *
 * @param <type> $pagos
 * @param <type> $subtotal
 * @param <type> $id_venta
 */
function payments_regularization( $pagos , $subtotal , $id_venta ) {
	$abonado = 0;
	$pagosBorrar = array();
	$abonoDiferencia = 0;
	
	foreach( $pagos as $pago ){
		$abonado += $pago->getMonto();
		
		if($abonado > $subtotal){
			array_push( $pagosBorrar , $pago->getIdPago() );
		}else{
			$abonoDiferencia += $pago->getMonto();
		}
	}//fin foreach
	
	$dim = count( $pagosBorrar );
	
	for( $i=0; $i < $dim; $i++ ){
		$pagoVenta = new PagosVenta();
		$pagoVenta->setIdPago($pagosBorrar[$i]);
		
		PagosVentaDAO::delete($pagoVenta);
	}//fin for
	
	$nuevoAbono = $subtotal - $abonoDiferencia;
	
	$pagoNuevo = new PagosVenta();
	$pagoNuevo->setIdVenta( $id_venta );
	$pagoNuevo->setMonto( $nuevoAbono );
	
	return PagosVentaDAO::save( $pagoNuevo );
}//fin payments_regularization

/**
 * EditItem_Existent_sale
 *
 * Funcion que actualiza directamente un detalle venta en su campo cantidad .
 * Modifica el inventario de la sucursal a la que pertenezca esa venta asi como 
 * actualizar el subotal e iva de la venta.
 * Si la venta fue a credito regulariza los pagos en dado caso que el nuevo subtotal
 * sea menor a los abonos que ha dado la sucursal para esa compra.
 *
 * @param <type> $id_venta
 * @param <type> $id_producto
 * @param <type> $precio
 * @param <type> $cantidad
 */
function EditItem_Existent_sale( $id_venta, $id_producto, $precio, $cantidad ) {
	
	if( !is_numeric($id_venta) || !is_numeric($id_producto) || !is_numeric($precio) || !is_numeric($cantidad) ){
		return "{success:false ,reason: 'Los datos proporcionados no son del tipo de dato requerido'}";
	}
	
	$detalle_venta = DetalleVentaDAO::getByPK( $id_venta, $id_producto );
	
	if ( !is_object($detalle_venta) ){	
		return "{success: false, reason: 'Los datos de esta compra no coinciden'}";
	}
	
	$dbCantidad = $detalle_venta->getCantidad(); //cantidad que hay en el detalle antes de modificar
	$dbPrecio = $detalle_venta->getPrecio();//precio que esta en el detalle antes de modificar
	
	$venta = VentasDAO::getByPK( $id_venta );
	$iventario = DetalleInventarioDAO::$getByPK( $id_producto , $venta->getIdSucursal() );
	
	
	$detalle_venta->setCantidad( $cantidad );
	
	$detalle_venta->setPrecio( $inventario->getPrecioVenta() );//toma el precio del inventario
	
	
	$res = DetalleVentaDAO::save( $detalle_venta );//save devulve false o un int
				
	if( $res < 1 )	{
		return "{success: false, reason:'No se modifico la cantidad y precio del producto'}";
	}
	
	$beforeCanti = $inventario->getExistencias(); //cantidad que esta en existencias antes de modificar
	$beforeSubtot = $compra->getSubtotal(); //cuando es de contado saber cuanto se devolvera de dinero
	$newExis = $beforeCanti;//se inicializa a como estaba el inventario por si no cambia existencias
				
	$detalle_inventario = new DetalleInventario();
	$detalle_inventario->setIdProducto( $id_producto );
	$detalle_inventario->setIdSucursal( $venta->getIdSucursal() );
	$detalle_inventario->setPrecioVenta( $inventario->getPrecioVenta() );
	$detalle_inventario->setMin( $inventario->getMin() );
					
	if( $cantidad > $dbCantidad ){
		$aux = $cantidad - $dbCantidad;
		$newExis = $beforeCanti + $aux;
	}
	if( $cantidad < $dbCantidad ){
		$aux = $dbCantidad - $cantidad;
		$newExis = $beforeCanti - $aux;
	}
					
	$detalle_inventario->setExistencias( $newExis );
					
	$ans = DetalleInventarioDAO::save( $detalle_inventario ) ;
	if( $ans < 1 )	{
		return "{success: false, reason:'No se actualizo el inventario'}";
	}

	$subtotal = 0;
	$items = new DetalleVenta();
	$items->setIdVenta( $id_venta );						
	$itemsCompra = DetalleVentaDAO::search( $items );
						
	foreach( $itemsCompra as $item ){
		$subtotal += $item->getCantidad() * $item->getPrecio();
	}//foreach
						
	$result = update_saleHeader($id_venta, $venta->getIdCliente() , $venta->getTipoVenta() , $subtotal );
			
	if($resul < 0) {
		return "{success:false , reason:'No se actualizo el subtotal, si inventario y detalle compra'}";
	}
			
	$abonado = 0;
	$devolver = 0;
	$pagos_venta = PagosVenta();
	$pagos_venta->setIdVenta($id_venta);
				
	$pagos = PagosVentaDAO::search( $pagos_venta );
	
	if( count($pagos) > 0 ){
		foreach($pagos as $pago){
			$abonado += $pago->getMonto();
		}//fin for
								
		if( $abonado > $subtotal ){
			$devolver = $abonado - $subtotal;
								
			$result = payments_regularization($pagos , $subtotal ,$id_venta );
			
			if( $result > 0 ){
				$out .=', se le adeuda al cliente la cantidad de: $ '.$devolver;
			}else{
				$out .=', No se regularizaron los pagos de esta venta, al cliente se le adeuda: $'.$devolver;
			}
		}
	}//fin regularization pagos
	
	if( $venta->getTipoVenta() == 'contado' ){
		$devolver = $beforeSubtot - $subtotal;
		$out .=', El proveedor debe devolverle a usted $ '.$devolver;
	}
	return sprintf ("{success : true , reason :'El producto cambio de cantidad %s'}",$out);
			
}//fin EditItem

/**
 * list_client_sales
 *
 * Funcion que enlista las ventas hechas a un cliente
 * 
 *
 * @param <type> $id_cliente
 */
function list_client_sales( $id_cliente ){

	//$sucursal = 2;

	$ventas = new Ventas();

	$ventas->setIdCliente( $id_cliente );
	

	$ventas_sucursal = VentasDAO::search( $ventas );
	
	$foo = "";
	
	if( count($ventas_sucursal) > 0 ){

		foreach($ventas_sucursal as $venta){
			
			$usuario = UsuarioDAO::getByPK( $venta->getIdUsuario() );
			
			// ESTE CALCULO SE HACE LLAMANDO A LA FUNCION GLOBAL !!!
			
			$total = $venta->getSubtotal() + $venta->getIva();
			$usuario = UsuarioDAO::getByPK( $venta->getIdUsuario() );
			$sc = SucursalDAO::getByPK( $venta->getIdSucursal() );

			$factura = new FacturaVenta();
			$factura->setIdVenta( $venta->getIdVenta() );

			$fact = FacturaVentaDAO::search( $factura );
			
			$facturado = 0;
			
			if( count($fact) > 0 ){	$facturado = 1;	}
			
			$foo .= substr($venta,1,-2);
			
			
			$hoy = ( date("Y-m-d H:i:s", time()) );
			/*
				FALTA SACAR LA DIFERENCIA DE LOS 30 DIAS PARA MANDAR EN JSON LA VARIABLE Q PERMITIRA O NO FACTURAR
			*/
			
			$foo.=',"total":"'.$total.'","nombre":"'.$usuario->getNombre().'","descripcion":"'.$sc->getDescripcion().'","facturado":"'.($facturado).'" ,"hoy":"'.$hoy.'"},';
		}//fin foreach
		
		$foo = substr($foo,0,-1);
		return " { success : true, datos : [".$foo."] }";
	}else{
		return " { success : false , reason: 'Este cliente no tiene ventas'}";
	}
}//fin list_client_sales

/**
 * sale_details
 *
 * Muestra la lista de productos que conforman una venta dado el
 * id de la venta
 *
 * @param <type> $id_venta
 */
function sale_details( $id_venta ){


	$venta = VentasDAO::getByPK( $id_venta );
	
	//obtener el nombre del vendedor
	$vendedor = UsuarioDAO::getByPK( $venta->getIdUsuario() )->getNombre();
	
	//obetner los items de la venta
	$query = new DetalleVenta();
	$query->setIdVenta( $id_venta );
	$detalles_venta = DetalleVentaDAO::search( $query );
	$items = array();
	
	foreach( $detalles_venta as $detail ){

		$producto = InventarioDAO::getByPK( $detail->getIdProducto() );
		
		array_push( $items, array(
				"id_producto" => $detail->getIdProducto(),
				"cantidad" => $detail->getCantidad(),
				"precio" => $detail->getPrecio(),
				"denominacion" => $producto->getDenominacion(),
				"nombre" => $producto->getNombre()				
			));
	}
	
	//crear el master json
	echo json_encode(  array(
		 		"success" => true,
				"id_venta" => $venta->getIdVenta(),
				"id_cliente" => $venta->getIdCliente(),
				"tipo_venta" => $venta->getTipoVenta(),
				"fecha" => $venta->getFecha(),
				"iva" => $venta->getIva(),				
				"subtotal" => $venta->getSubtotal(),
				"descuento" => $venta->getDescuento(),	
				"total" => $venta->getTotal(),	
				"id_sucursal" => $venta->getIdSucursal(),
				"vendedor" => $vendedor,	
				"pagado" => $venta->getPagado(),
				"items" => $items																																										
			));

}//fin sale_details

/**
 * sale_payments
 *
 * Muestra los abonos hechos en una venta dado el
 * id de la venta
 *
 * @param <type> $id_venta
 */
function sale_payments( $id_venta ){
	$pagos = new PagosVenta();
	$pagos->setIdVenta( $id_venta );
	
	$pagos_venta = PagosVentaDAO::search( $pagos );
	$out ="";
	
	if(count($pagos_venta)>0){
		foreach( $pagos_venta as $pago ){
			$out.= substr( $pago, 1, -1 );//[{jgkjgk}] -> {jgkjgk}
			$out.=","; //{jgkjgk} -> {jgkjgk},
		}
		$data ="[";
		$data .= substr($out,0,-1)."]"; // [{jgkjgk},{jgkjgk},{jgkjgk},] -> [{jgkjgk},{jgkjgk},{jgkjgk}]
		return "{ success : true, datos : ". $data."}";
	}else{
		return "{ success : false , reason: 'Esta venta no tiene abonos'}";
	}
}//fin sale_payments


/**
 * credit_clientSales
 *
 * Muestra las ventas a credito hechos a un cliente en especifico
 * al igual que desplega los abonos que se le han hecho por cada venta a credito
 *
 * @param <type> $id_cliente
 */
function credit_clientSales( $id_cliente ){


	$out = ""; 
	$sucursal= $_SESSION['sucursal'];
	//$sucursal= 2;
	
	$venta = new Ventas();
	$venta->setIdCliente( $id_cliente );
	//$venta->setIdSucursal( $sucursal );	
	$venta->setTipoVenta( 'credito' );	
	$ventas = VentasDAO::search( $venta );
	
	if( count($ventas) > 0 ){

		foreach( $ventas as $v )
		{
			$total_pagos = 0;
			$sale_payment = new PagosVenta();
			$sale_payment->setIdVenta( $v->getIdVenta() );
			$pv = PagosVentaDAO::search( $sale_payment );

			if ( count($pv) > 0 ){
				foreach( $pv as $pago )
				{
					$total_pagos += $pago->getMonto();
				}
			}
			$cliente = ClienteDAO::getByPK( $v->getIdCliente() );
			$totVenta = $v->getSubtotal() + $v->getIva();
			$adeudo = $totVenta - $total_pagos;
			$usuario = UsuarioDAO::getByPK( $v->getIdUsuario() );
			$sc = SucursalDAO::getByPK( $v->getIdSucursal() );
			
			$out .= substr($v,1,-2);

			$out.=',"total":"'.$totVenta.'","abonado":"'.$total_pagos.'","adeudo":"'.$adeudo.'","nombre":"'.$cliente->getNombre().'","vendedor":"'.$usuario->getNombre().'","sucursal":"'.$sc->getDescripcion().'"},';
	
		}//fin foreach ventas
		$out = substr($out,0,-1);
		return " { success : true, datos : [".$out."] }";
	}else{
		return " { success : false , reason: 'No se le han hecho ventas a credito a este cliente'}";
	}
	
}//fin credit_clientSales


/**
 * existenciaProductoSucursal
 * itemExistence_sucursal
 *
 * Regresa las existencias de un producto en una sucursal, 
 * recibiendo el id del producto y el de la sucursal lo saca de la session
 *
 * @param <type> $id_producto
 */
function itemExistence_sucursal( $id_producto ){
	$sucursal= $_SESSION['sucursal'];
	//$sucursal= 2;
	
	$producto = DetalleInventarioDAO::getByPK( $id_producto, $sucursal );
	$inventario = InventarioDAO::getByPK( $id_producto );
	
	
	
	if( $producto->getExistencias() > 0 ){
		$jsonArray = array();
		
		array_push( $jsonArray, array("id_producto"=>$id_producto, "nombre"=>$inventario->getNombre(), "denominacion"=>$inventario->getDenominacion(), "precio_venta"=>$producto->getPrecioVenta(), "existencias"=>$producto->getExistencias() ) );
		
		return " { success : true, datos : ". json_encode($jsonArray)."}";
		
	}else{
		return "{success: false, reason:'Este producto no esta disponible' }";
	}
}

/**
 * sale_header
 *
 * Trae la cabecera de la venta dado su id
 *
 * @param <type> $id_venta
 */
function sale_header( $id_venta ){
	$venta = VentasDAO::getByPK( $id_venta );
	
	if( is_object($venta)  ){
		$out = '{ "tipo_venta":"'.$venta->getTipoVenta().'" , ';
		$out .= '"fecha":"'.$venta->getFecha().'", ';
		$out .= '"subtotal":"'.$venta->getSubtotal().'", ';
		$out .= '"iva":"'.$venta->getIva().'", ';
		$out .= '"descuento":"'.$venta->getDescuento().'", ';
		$out .= '"total":"'.$venta->getTotal().'", ';
		$out .= '"pagado":"'.$venta->getPagado().'"} ';
		return '{success: true, datos : [ '.$out.' ] }';
	}else{
		return "{success: false, reason: 'Esta venta no existe' }";
	}
}





switch ($args['action']) {
    case '1401':
        $id_cliente = $args['id_cliente'];
        
		
        $ans = list_client_sales( $id_cliente );
        echo $ans;
	break;
	
	case '1402':
		echo sale_details( $args['id_venta'] );

	break;
	
	case '1403':
		$id_cliente = $args['id_cliente'];
        
		
        $ans = credit_clientSales( $id_cliente );
        echo $ans;
	break;
	
	case '1404':
		$id_venta = $args['id_venta'];
        
		
        $ans = sale_payments( $id_venta );
        echo $ans;
	break;
	
	case '1405':
		$id_venta = $args['id_venta'];
		$monto = $args['monto'];
        
		include_once("pagos_ventas.controller.php");
        $ans = insert_payment( $id_venta, $monto );
        echo $ans;
	break;
	
	case '1406':
		$id_pago = $args['id_pago'];
		
		include_once("pagos_ventas.controller.php");
		$ans = delete_payment($id_pago);
		echo $ans;
	break;
	
	case '1407':
		$id_venta = $args['id_venta'];
		
		
		$ans = sale_header( $id_venta );
		echo $ans;
	break;
}//end switch
?>