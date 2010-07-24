<?php
/** Compras Controller.
  * 
  * Este script contiene las funciones necesarias para realizar las diferentes operaciones 
  * que se pueden hacer durante una compra a un proveedor, Insertar, agregar productos a compra
  * y eliminar productos de compra entre otras.
  * @author Juan Manuel Hernadez <manuel@caffeina.mx> 
  * 
  */
/**
 * Se importan las clases necesarias para la instanciacion de los objetos que son 
 * importantes en una compra.
 */
require_once('../model/base/compras.vo.base.php');
require_once('../model/base/detalle_compra.vo.base.php');
require_once('../model/base/detalle_inventario.vo.base.php');
require_once('../model/base/pagos_compra.vo.base.php');
require_once('../model/base/productos_proveedor.vo.base.php');
/**
 * Se importan los DAO para poder realizar las operaciones sobre la BD.
 */
require_once('../model/compras.dao.php');
require_once('../model/detalle_compra.dao.php');
require_once('../model/detalle_inventario.dao.php');
require_once('../model/pagos_compra.dao.php');
require_once('../model/productos_proveedor.dao.php');
/**
 * insert_purchase
 *
 * Funcion que inserta una nueva compra, modifica el inventario de la sucursal
 * y calcula el iva correspondiente de la venta de acuerdo al valor del impuesto
 * establecido en la BD.
 * La insercion de la compra varia del modo_compra, si es false, la compra se
 * registra por kgs y precio por kg segun lo acordado con el proveedor.
 * Si es modo_compra  es true, registra la compra del producto por cantidad (pieza) y al
 * precio que este registrado ese producto en la BD.
 *
 * @param <type> $jsonItems
 * @param <type> $id_proveedor
 * @param <type> $tipo_compra
 * @param <type> $modo_compra
 */

		
function insert_purchase($jsonItems, $id_proveedor, $tipo_compra, $modo_compra) {//01
	 $sucursal=$_SESSION['sucursal_id'];
	 $id_usuario=$_SESSION['id_usuario'];
	 $out="";
	 $compra = new Compra();
	 
	 $compra->setIdProveedor($id_proveedor);
	 $compra->setTipoCompra($tipo_compra);
	 $compra->setSubtotal(0);
	 $compra->setIva(0);
	 $compra->setIdSucursal($sucursal);
	 $compra->setIdUsuario($id_usuario);
	 
	 $ans = ComprasDAO::save($compra);
	 
	 if($ans){
		 
		 $id_compra = compra->getIdCompra();
		 $arregloItems = json_decode($jsonItems,true);
		 $dim = count($arregloItems);
		 $subtotalCompra =0;

		for($i=0; $i < $dim; $i++){
			
			if(!$modo_compra){// TOÑO MODE
				
				$inventario = ( $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'] ) * $arregloItems[$i]['nA'];
				
				$pesoArpReal = $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'];
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($inventario);
				$detalle_compra->setPrecio($arregloItems[$i]['prKg']);
				$detalle_compra->setPesoArpillaPagado($arregloItems[$i]['pesoArp']);
				$detalle_compra->setPesoArpillaReal($pesoArpReal);
				
				$resul = DetalleCompraDAO::save($detalle_compra);
				
				if($resul){
					
					$detalle_inventario = DetalleInventarioDAO::getByPK($arregloItems[$i]['id'],$sucursal);
					$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() + $inventario);
					$res = DetalleInventarioDAO::save($detalle_inventario);
					
					if($res){
						$subtotalCompra += $arregloItems[$i]['prKg'] * $arregloItems[$i]['kgTot'];
					}else{//if res
						$out .=" - No se agrego al inventario el producto con id: ".$arregloItems[$i]['id'];
					}
				}else{//if resul
					$out .= " - No se inserto el producto con id: ".$arregloItems[$i]['id'];
				}
				
			}else{//if else modo_compra GENERAL MODE
								
				$cantidad = $arregloItems[$i]['cantidad'];
				$precioP = ProductosProveedorDAO::product_price($arregloItems[$i]['id'], $id_proveedor, $sucursal );
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($cantidad);
				$detalle_compra->setPrecio($precioP);
				$detalle_compra->setPesoArpillaPagado(0);
				$detalle_compra->setPesoArpillaReal(0);
				
				$resul = DetalleCompraDAO::save($detalle_compra);
				
				if($resul){
					
					$detalle_inventario = DetalleInventarioDAO::getByPK($arregloItems[$i]['id'],$sucursal);
					
					$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() + $cantidad);
					
					$res = DetalleInventarioDAO::save($detalle_inventario);
					
					if($res){
						$subtotalCompra += $cantidad * $precioP;
					}else{//if res
						$out .=" - No se agrego al inventario el producto con id: ".$arregloItems[$i]['id'];
					}
				}else{//fin resul
					$out .= " - No se inserto el producto con id: ".$arregloItems[$i]['id'];
				}
			}//fin else GENERAL MODE
		}//fin for
		
		
		$actCabeceraCompra = update_purchaseHeader($id_compra, $id_proveedor, $tipo_compra, $subtotalCompra);
		
		if ( !$actCabeceraCompra ){
			return sprintf "{success: false , reason: 'No se modifico el subtotal e iva de la compra', details: '%s'}",$out;
		}else{		
			return sprintf "{ success : true , reason :'Compra registrada completamente' , details:'%s'}",$out;
		}
	 }else{//if ans
	 	return "{success: false , reason 'No se inserto la compra ni sus detalles, ni en inventario'}";
	 }
}//insert_purchase

/**
 * update_purchaseHeader
 *
 * Actualiza la tabla compras con el subtotal e IVA correspondiente de una
 * compra existente. Se usa cuando se inserta o elimina un producto de una compra
 * asi como cuando se hacen devoluciones de producto. 
 * Esta funcion es llamada por las funciones insert_purchase , addItems_Existent_purchase
 *
 * @param <type> $id_compra
 * @param <type> $id_proveedor
 * @param <type> $tipo_compra
 * @param <type> $subtotal
 */
function update_purchaseHeader($id_compra, $id_proveedor, $tipo_compra, $subtotal ) { 
	$sucursal=$_SESSION['sucursal_id'];
	$id_usuario=$_SESSION['id_usuario'];
	
	$iva = ImpuestoDAO::getByPK(5);//en mi bd el iva es el id 5
		
	$compras = new Compras();
	
	$compras->setIdCompra($id_compra);
	$compras->setIdProveedor($id_proveedor);
	$compras->setTipoCompra($tipo_compra);
	$compras->setSubtotal($subtotal);
	$compras->setIva( ( $iva->getValor() / 100 ) * $subtotal );
	$compras->setIdSucursal($sucursal);
	$compras->IdUsuario($id_usuario);

	$res = ComprasDAO::save($compras);//regresa false o un int
	
	return $res;
}

/**
 * delete_purchase
 *
 * Elimina una compra existente hecha a un proveedor
 *
 * @param <type> $id_compra
 */
function delete_purchase($id_compra) { 
	
   $res = ComprasDAO::delete($id_compra);
   if($res){
	   return "{success: true , reason: 'Se elimino la compra satisfactoriamente'}";
   }else{
	   return "{success: false , reason: 'No se pudo eliminar la compra'}";
   }
}//fin delete_purchase


/**
 * addItems_Existent_purchase
 *
 * Funcion que inserta un nuevo producto a una compra existente, 
 * modifica el inventario de la sucursal y calcula el iva correspondiente 
 * de la venta de acuerdo al valor del impuesto establecido en la BD.
 * La insercion  del producto en la compra varia del modo_compra, si es false, el producto se
 * registra por kgs y precio por kg segun lo acordado con el proveedor.
 * Si es modo_compra  es true, registra el producto por cantidad (pieza) y al
 * precio que este registrado ese producto en la BD.
 *
 * @param <type> $id_compra
 * @param <type> $id_producto
 * @param <type> $cantidad
 * @param <type> $precio
 * @param <type> $pesoArpPagado
 * @param <type> $pesoArpReal
 */
function addItems_Existent_purchase($jsonItems,$id_compra, $id_proveedor, $tipo_compra, $modo_compra) {
	
	$arregloItems = json_decode($jsonItems,true);
	$dim = count($arregloItems);
	$out = "";
    
	
	$subtotalCompra =0;

		for($i=0; $i < $dim; $i++){
			
			if(!$modo_compra){// TOÑO MODE
				
				$inventario = ( $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'] ) * $arregloItems[$i]['nA'];
				
				$pesoArpReal = $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'];
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($inventario);
				$detalle_compra->setPrecio($arregloItems[$i]['prKg']);
				$detalle_compra->setPesoArpillaPagado($arregloItems[$i]['pesoArp']);
				$detalle_compra->setPesoArpillaReal($pesoArpReal);
				
				$resul = DetalleCompraDAO::save($detalle_compra);
				
				if($resul){
					
					$detalle_inventario = DetalleInventarioDAO::getByPK($arregloItems[$i]['id'],$sucursal);
					$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() + $inventario);
					$res = DetalleInventarioDAO::save($detalle_inventario);
					
					if(!$res){
						$out .=" - No se agrego al inventario el producto con id: ".$arregloItems[$i]['id'];
					}
				}else{//if resul
					$out .= " - No se inserto el producto con id: ".$arregloItems[$i]['id'];
				}
				
			}else{//if else modo_compra GENERAL MODE
				
				$cantidad = $arregloItems[$i]['cantidad'];
				$precioP = ProductosProveedorDAO::product_price($arregloItems[$i]['id'], $id_proveedor, $sucursal );
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($cantidad);
				$detalle_compra->setPrecio($precioP);
				$detalle_compra->setPesoArpillaPagado(0);
				$detalle_compra->setPesoArpillaReal(0);
				
				$resul = DetalleCompraDAO::save($detalle_compra);
				
				if($resul){
					
					$detalle_inventario = DetalleInventarioDAO::getByPK($arregloItems[$i]['id'],$sucursal);
					$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() + $cantidad);
					$res = DetalleInventarioDAO::save($detalle_inventario);
					
					if(!$res){
						$out .=" - No se agrego al inventario el producto con id: ".$arregloItems[$i]['id'];
					}
				}else{//fin resul
					$out .= " - No se inserto el producto con id: ".$arregloItems[$i]['id'];
				}
			}//fin else GENERAL MODE
		}//fin for
		
		$compra->setIdCompra($id_compra);
		$detallesCompra = DetalleCompraDAO::search($compra);
		$subtotal = 0;
		foreach($detallesCompra as $detalle){
			$subtotal += $detalle->getCantidad() * $detalle->getPrecio();
		}//foreach
		
		$actCabeceraCompra = update_purchaseHeader($id_compra, $id_proveedor, $tipo_compra, $subtotal);
		
		if ( !$actCabeceraCompra ){
			return sprintf "{success: false , reason: 'No se modifico el subtotal e iva de la compra', details: '%s'}",$out;
		}else{		
			return sprintf "{ success : true , reason :'Compra registrada completamente' , details:'%s'}",$out;
		}
	
	
}//fin addItem_purchase

/**
 * removeItem_Existent_purchase
 *
 * Funcion que elimina un producto que esta en una compra existente, 
 * modifica el inventario de la sucursal y calcula el subtotal e iva correspondiente 
 * de la venta de acuerdo al valor del impuesto establecido en la BD.
 * En dado caso que la eliminacion de ese producto de esa venta implica
 * que haya devolucion de dinero del proveedor hacia la sucursal debido
 * a que fue de contado o los abonos no coinciden con el subtotal de la compra,
 * la funcion regulariza los pagos de la compra y arroja la cantidad a devolver
 * en el json.
 *
 * @param <type> $jsonItems
 */
function removeItem_Existent_purchase($jsonItems) {
	
	$arregloItems = json_decode($jsonItems,true);
	$dim = count($arregloItems);
	$subtotal = 0;
    
	$detalle_compra= DetalleCompraDAO::getByPK($arregloItems[0]['id_compra'],$arregloItems[0]['id']);
	
	$cantidad = $detalle_compra->getCantidad();
	$id_compra = $detalle_compra->getIdCompra();
	$id_producto = $detalle_compra->getIdProducto();
	
	$ans = DetalleCompraDAO::delete($detalle_compra);
	
	if($ans){
		
		$compra = ComprasDAO::getByPK($id_compra);
		$beforeSubtot = $compra->getSubtotal();//para comparar con lo que ha abonado
		$tipoCompra = $compra->getTipoCompra();
		
		$detallesCompra = DetalleCompraDAO::search($compra->getIdCompra());
		
		foreach($detallesCompra as $detalle){
			$subtotal += $detalle->getCantidad() * $detalle->getPrecio();
		}//foreach
		
		$sucursal=$compra->getIdSucursal();
		
		$detalle_inventario = DetalleInventarioDAO::getByPK( $id_producto , $sucursal );
		$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() - $cantidad );
		$res = DetalleInventarioDAO::save($detalle_inventario);
		
		if($res){
			$actCabeceraCompra = update_purchaseHeader($compra->getIdCompra(), $compra->getIdProveedor(), $compra->getTipoCompra(), $subtotal);
				
			if ( !$actCabeceraCompra ){
				return "{success: false , reason: 'No se modifico el subtotal e iva de la compra'}";
			}
				
			$abonado = 0;
			$devolver = 0; 
			$out = "";
				
			$pagos_compra = PagosCompra();
			$pagos_compra->setIdCompra($id_compra);
				
			$pagos = PagosCompraDAO::search( $pagos_compra );
				
			foreach($pagos as $pago){
				$abonado += $pago->getMonto();
			}//fin for
				
			if( $abonado > $subtotal ){
				$devolver = $abonado - $subtotal;
					
				payments_regularization($pagos , $subtotal ,$id_compra );
					
				$out .=', El proveedor debe devolverle a usted $ '.$devolver;
			}
			if( $tipoCompra == 'contado' ){
				$devolver = $beforeSubtot - $subtotal;
				$out .=', El proveedor debe devolverle a usted $ '.$devolver;
			}
				
				
			return sprintf "{success : true , reason :'Producto eliminado completamente de la compra %s'}",$out;
			
			
		}else{//else res
			return "{success: false , reason:'No se Modifico el inventario tras la eliminacion de este producto'}";
		}
	}else{//else ans
		return "{success: false, reason: 'No se elimino el producto de la compra' }";
	}
	
}//fin removeItem_purchase

/**
 * payments_regularization
 *
 * Funcion que regulariza los pagos de una compra dada con respecto al nuevo subtotal. 
 * Se ejecuta solamente si la compra fue de contado o los abonos exceden el subtotal.
 * 
 *
 * @param <type> $pagos
 * @param <type> $subtotal
 * @param <type> $id_compra
 */
function payments_regularization( $pagos , $subtotal , $id_compra ) {
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
		$pagoCompra = new PagosCompra();
		$pagoCompra->setIdPago($pagosBorrar[$i]);
		
		PagosCompraDAO::delete($pagoCompra);
	}//fin for
	
	$nuevoAbono = $subtotal - $abonoDiferencia;
	
	$pagoNuevo = new PagosCompra();
	$pagoNuevo->setIdCompra( $id_compra );
	$pagoNuevo->setMonto( $nuevoAbono );
	
	PagosCompraDAO::save( $pagoNuevo );
}//fin payments_regularization

/**
 * EditItem_Existent_purchase
 *
 * Funcion que actualiza directamente un detalle compra en su campo cantidad y en dado
 * caso que se compre por kgs tambien estara la opcion de cambiar el precio por kg.
 * Modifica el inventario de la sucursal a la que pertenezca esa compra asi como 
 * actualizar el subotal e iva de la compra.
 * Si la compra fue a credito regulariza los pagos en dado caso que el nuevo subtotal
 * sea menor a los abonos que ha dado la sucursal para esa compra.
 *
 * @param <type> $id_compra
 * @param <type> $id_producto
 * @param <type> $precio
 * @param <type> $cantidad
 */
function EditItem_Existent_purchase( $id_compra, $id_producto, $precio, $cantidad ) {
	
	if( !is_int($id_compra) || !is_int($id_producto) || !is_float($precio) || !is_float($cantidad) ){
		return "{success:false ,reason: 'Los datos proporcionados no son del tipo de dato requerido'}";
	}
	
	$detalle_compra = DetalleCompraDAO::getByPK( $id_compra, $id_producto );
	
	if ( !is_object($detalle_compra) ){	
		return "{success: false, reason: 'Los datos de esta compra no coinciden'}";
	}
	
	$dbCantidad = $detalle_compra->getCantidad(); //cantidad que hay en el detalle antes de modificar
	$dbPrecio = $detalle_compra->getPrecio();//precio que esta en el detalle antes de modificar
	
	$compra = ComprasDAO::getByPK( $id_compra );
	$iventario = DetalleInventarioDAO::$getByPK( $id_producto , $compra->getIdSucursal() );
	
	//VER SI CAMBIARA EL PESO POR ARPILLA REAL Y PAGADO
	
	$detalle_compra->setCantidad( $cantidad );
	
	if( $detalle_compra->getPesoArpillaPagado() != 0 && $detalle_compra->getPesoArpillaReal() != 0 ){//por kg, puede cambiar el precio del producto
		$detalle_compra->setPrecio( $precio );
	}else{//es por pieza no cambia el precio
		$detalle_compra->setPrecio( $inventario->getPrecioVenta() );
	}
	
	$res = DetalleCompraDAO::save( $detalle_compra );//save devulve false o un int
				
	if( $res < 1 )	{
		return "{success: false, reason:'No se modifico la cantidad y precio del producto'}";
	}
	
	$beforeCanti = $inventario->getExistencias(); //cantidad que esta en existencias antes de modificar
	$beforeSubtot = $compra->getSubtotal(); //cuando es de contado saber cuanto se devolvera de dinero
	$newExis = $beforeCanti;//se inicializa a como estaba el inventario por si no cambia existencias
				
	$detalle_inventario = new DetalleInventario();
	$detalle_inventario->setIdProducto( $id_producto );
	$detalle_inventario->setIdSucursal( $compra->getIdSucursal() );
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
	$items = new DetalleCompra();
	$items->setIdCompra( $id_compra );						
	$itemsCompra = DetalleCompraDAO::search( $items );
						
	foreach( $itemsCompra as $item ){
		$subtotal += $item->getCantidad() * $item->getPrecio();
	}//foreach
						
	$result = update_purchaseHeader($id_compra, $compra->getIdProveedor() , $compra->getTipoCompra() , $subtotal );
			
	if($resul < 0) {
		return "{success:false , reason:'No se actualizo el subtotal, si inventario y detalle compra'}";
	}
			
	$abonado = 0;
	$devolver = 0;
	$pagos_compra = PagosCompra();
	$pagos_compra->setIdCompra($id_compra);
				
	$pagos = PagosCompraDAO::search( $pagos_compra );
							
	foreach($pagos as $pago){
		$abonado += $pago->getMonto();
	}//fin for
							
	if( $abonado > $subtotal ){
		$devolver = $abonado - $subtotal;
							
		payments_regularization($pagos , $subtotal ,$id_compra );
							
		$out .=', El proveedor debe devolverle a usted $ '.$devolver;
	}
	if( $compra->getTipoCompra() == 'contado' ){
		$devolver = $beforeSubtot - $subtotal;
		$out .=', El proveedor debe devolverle a usted $ '.$devolver;
	}
	return sprintf "{success : true , reason :'El producto cambio de cantidad %s'}",$out;
			
}//fin payments_regularization

/**
 * list_sucursal_purchases
 *
 * Funcion que enlista las compras hechas a un proveedor para una
 * sucursal en especifico
 *
 * @param <type> $id_proveedor
 */
function list_sucursal_purchases( $id_proveedor ){
	
	$sucursal = $_SESSION['id_sucursal'];
	
	$result = ComprasDAO::list_purchases( $id_proveedor , $sucursal );
	
	if(count($result)>0){
		return " { success : true, datos : ". json_encode($result)."}";
	}else{
		return " { success : false , reason: 'No se le ha comprado a este proveedor'}";
	}
}//fin list_sucursal_purchases

/**
 * purchase_details
 *
 * Muestra la lista de productos que conforman una compra dado el
 * id de la compra
 *
 * @param <type> $id_compra
 */
function purchase_details( $id_compra ){
	
	$result = ComprasDAO::get_purchase_details( $id_compra );
	
	if(count($result)>0){
		return " { success : true, datos : ". json_encode($result)."}";
	}else{
		return " { success : false , reason: 'Esta compra no tiene productos'}";
	}
}//fin purchase_details

/**
 * purchase_payments
 *
 * Muestra los abonos hechos en una venta dado el
 * id de la compra
 *
 * @param <type> $id_compra
 */
function purchase_payments( $id_compra ){
	
	$result = ComprasDAO::get_purchase_payments( $id_compra );
	
	if(count($result)>0){
		return " { success : true, datos : ". json_encode($result)."}";
	}else{
		return " { success : false , reason: 'Esta compra no tiene abonos'}";
	}
}//fin purchase_payments


/**
 * get_credit_providerpurchases
 *
 * Muestra las compras a credito hechos a un proveedor en especifico
 * al igual que desplega los abonos que se le han hecho por cada compra a credito
 *
 * @param <type> $id_proveedor
 */
function credit_providerPurchases( $id_proveedor ){
	
	$result = ComprasDAO::get_credit_providerpurchases( $id_proveedor );
	
	if(count($result)>0){
		return " { success : true, datos : ". json_encode($result)."}";
	}else{
		return " { success : false , reason: 'No se le han hecho compras a credito a este proveedor'}";
	}
}//fin purchase_payments

?>