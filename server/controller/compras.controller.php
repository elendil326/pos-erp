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
 * Se importan los DAO para poder realizar las operaciones sobre la BD.
 */ 
require_once('../server/model/compras.dao.php');
require_once('../server/model/detalle_compra.dao.php');
require_once('../server/model/detalle_inventario.dao.php');
require_once('../server/model/pagos_compra.dao.php');
require_once('../server/model/productos_proveedor.dao.php');
require_once('../server/model/inventario.dao.php');
require_once('../server/model/impuesto.dao.php');
require_once('../server/model/sucursal.dao.php');
require_once('../server/model/usuario.dao.php');
require_once('../server/model/proveedor.dao.php');
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
	 $sucursal=$_SESSION['sucursal'];
	 $id_usuario=$_SESSION['userid'];
	 //$sucursal=2;
	 //$id_usuario=1;
	 $out="";
	 $compra = new Compras();
	 
	 $compra->setIdProveedor($id_proveedor);
	 $compra->setTipoCompra($tipo_compra);
	 $compra->setSubtotal(0);
	 $compra->setIva(0);
	 $compra->setIdSucursal($sucursal);
	 $compra->setIdUsuario($id_usuario);
	 
	 try{
	 $ans = ComprasDAO::save($compra);
	 
	 }catch(Exception $e){
		 return "{success:false, reason:'No se inserto la cabecera de la compra Detalles: ".$e->getMessage()."'}";
	 }
	 if( $ans > 0 ){
		 
		 $id_compra = $compra->getIdCompra();
		 $arregloItems = json_decode($jsonItems,true);
		 $dim = count($arregloItems);
		 $subtotalCompra =0;

		for($i=0; $i < $dim; $i++){
			
			if($modo_compra == 'false' ){// TOÑO MODE

				$inventario = ( $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'] ) * $arregloItems[$i]['nA'];
				
				$pesoArpReal = $arregloItems[$i]['pesoArp'] + $arregloItems[$i]['kgR'];
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($arregloItems[$i]['kgTot']);
				$detalle_compra->setPrecio($arregloItems[$i]['prKg']);
				$detalle_compra->setPesoArpillaPagado($arregloItems[$i]['pesoArp']);
				$detalle_compra->setPesoArpillaReal($pesoArpReal);
				
				try{
				$resul = DetalleCompraDAO::save($detalle_compra);
				}catch(Exception $e){
					return "{success:false, reason:'No se inserto el detalle de la compra Detalles: ".$e->getMessage()."'}";
				}
				if( $resul > 0 ){
					
					$detalle_inventario = DetalleInventarioDAO::getByPK($arregloItems[$i]['id'],$sucursal);
					$detalle_inventario->setExistencias( $detalle_inventario->getExistencias() + $inventario);
					$res = DetalleInventarioDAO::save($detalle_inventario);
					
					if( $res > 0 ){
						$subtotalCompra += $arregloItems[$i]['prKg'] * $arregloItems[$i]['kgTot'];
					}else{//if res
						$out .=" - No se agrego al inventario el producto con id: ".$arregloItems[$i]['id'];
					}
				}else{//if resul
					$out .= " - No se inserto el producto con id: ".$arregloItems[$i]['id'];
				}
				
			}else{//if else modo_compra GENERAL MODE	
				$cantidad = $arregloItems[$i]['cantidad'];
				//sacar el precio a como lo da el proveedor
				$productoProveedor = new ProductosProveedor();
				$productoProveedor->setIdProveedor( $id_proveedor );
				$productoProveedor->setIdInventario( $arregloItems[$i]['id'] );
				
				$pP = ProductosProveedorDAO::search( $productoProveedor );
				
				$precioP = $pP[0]->getPrecio();
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($cantidad);
				$detalle_compra->setPrecio($precioP);
				
				$resul = DetalleCompraDAO::save($detalle_compra);
				
				if( $resul > 0 ){
					
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
		
		if ( $actCabeceraCompra < 1 ){
			return sprintf ("{success: false , reason: 'No se modifico el subtotal e iva de la compra', details: '%s'}",$out);
		}else{		
			return sprintf ("{ success : true , reason :'Compra registrada completamente' , details:' %s '}",$out);
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
	$sucursal=$_SESSION['sucursal'];
	$id_usuario=$_SESSION['userid'];
	//$sucursal=2;
	//$id_usuario=1;
	 
	$iva = ImpuestoDAO::getByPK(1);//en mi bd el iva es el id 1
		
	$compras = new Compras();
	
	$compras->setIdCompra($id_compra);
	$compras->setIdProveedor($id_proveedor);
	$compras->setTipoCompra($tipo_compra);
	$compras->setSubtotal($subtotal);
	$compras->setIva( ( $iva->getValor() / 100 ) * $subtotal );
	$compras->setIdSucursal($sucursal);
	$compras->setIdUsuario($id_usuario);

	$res = ComprasDAO::save($compras);//regresa false o un int
	
	return $res;
}

/**
 * delete_purchase
 *
 * Elimina una compra existente hecha a un proveedor (como si nunca se hubiera hecho, se registro mal)
 * no se actualiza el almacen pero si se eliminan los pagos a esta compra si es que hay
 *
 * @param <type> $id_compra
 */
function delete_purchase($id_compra) { 

	$detalles_compra = new DetalleCompra();
	$detalles_compra->setIdCompra( $id_compra );
	$compras_borrar = DetalleCompraDAO::search( $detalles_compra );
	
	$subtotal = 0;
	
	foreach( $compras_borrar as $detalle ){
		DetalleCompraDAO::delete( $detalle );
	}
	
	$compra = new Compras();
	$compra->setIdCompra( $id_compra );
	
	if( $compra->getTipoCompra() == 'credito' ){
		$pagos = new PagosCompra();
		$pagos->setIdCompra( $id_compra );
		
		$pagos_compra = PagosCompraDAO::search( $pagos );
		
		
		if( count($pagos_compra) > 0 ){
			foreach( $pagos_compra as $pago){
				$subtotal += $pago->getMonto();
				PagosCompraDAO::delete( $pago );
			}
		}
	}else{
		$subtotal = $compra->getSubtotal();
	}
	
	$res = ComprasDAO::delete($compra);
   
   	if( $res > 0 ){
	   return "{success: true , reason: 'Se elimino la compra satisfactoriamente, se pago a esta compra un total de: $ ".$subtotal."'}";
   	}else{
	   return "{success: false , reason: 'No se pudo eliminar la compra'}";
   	}
}//fin delete_purchase

/**

 * entire_devolution_purchase
 *
 * Elimina una compra existente hecha a un proveedor y se quitan los productos al 
 * almacen (devolucion completa) asi como eliminar los pagos hechos a esta compra
 *
 * @param <type> $id_compra
 */
function entire_devolution_purchase($id_compra) { 
	
	$detalles_compra = new DetalleCompra();
	$detalles_compra->setIdCompra( $id_compra );
	$compras_borrar = DetalleCompraDAO::search( $detalles_compra );
	
	$compra_delete = ComprasDAO::getByPK( $id_compra );
	$suc = $compra_delete->getIdSucursal();
	$subtotal = 0;
	
	foreach( $compras_borrar as $detalle ){
		
		$detalle_inventario = DetalleInventarioDAO::getByPK( $detalle->getIdProducto(), $suc );
		$existencias = $detalle_inventario->getExistencias();
		$detalle_inventario->setExistencias( ( $existencias - $detalle->getCantidad() ) );
		
		DetalleVentaDAO::delete( $detalle );
	}
	
	if( $compra_delete->getTipoCompra() == 'credito' ){
		$pagos = new PagosVenta();
		$pagos->setIdCompra( $id_compra );
		
		$pagos_compra = PagosCompraDAO::search( $pagos );
		
		if( count($pagos_compra) > 0 ){
			foreach( $pagos_compra as $pago){
				$subtotal += $pago->getMonto();
				PagosCompraDAO::delete( $pago );
			}
		}
	}else{
		$subtotal = $compra_delete->getSubtotal();
	}
	$res = ComprasDAO::delete($compra_delete);
   
   if($res > 0){
	   return "{success: true , reason: 'Se elimino la compra satisfactoriamente, se pago a esta compra un total de: $ ".$subtotal."'}";
   }else{
	   return "{success: false , reason: 'No se pudo eliminar la compra'}";
   }
}//fin entire_devolution_purchase


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
 * @param <type> $jsonItems
 * @param <type> $id_compra
 * @param <type> $id_proveedor
 * @param <type> $tipo_compra
 * @param <type> $modo_compra
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
				
				if( $resul > 0 ){
					
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
				//sacar el precio a como lo da el proveedor
				$productoProveedor = new ProductosProveedor();
				$productoProveedor->setIdProveedor( $id_proveedor );
				$productoProveedor->setIdInventario( $arregloItems[$i]['id'] );
				
				$pP = ProductosProveedorDAO::search( $productoProveedor );
				
				$precioP = $pP[0]->getPrecio();
				
				$detalle_compra = new DetalleCompra();
				
				$detalle_compra->setIdCompra($id_compra);
				$detalle_compra->setIdProducto($arregloItems[$i]['id']);
				$detalle_compra->setCantidad($cantidad);
				$detalle_compra->setPrecio($precioP);
				$detalle_compra->setPesoArpillaPagado(0);
				$detalle_compra->setPesoArpillaReal(0);
				
				$resul = DetalleCompraDAO::save($detalle_compra);
				
				if( $resul > 0 ){
					
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
		
		$compra = new DetalleCompra();
		$compra->setIdCompra($id_compra);
		$detallesCompra = DetalleCompraDAO::search($compra);
		
		$subtotal = 0;
		
		foreach($detallesCompra as $detalle){
			$subtotal += $detalle->getCantidad() * $detalle->getPrecio();
		}//foreach
		
		$actCabeceraCompra = update_purchaseHeader($id_compra, $id_proveedor, $tipo_compra, $subtotal);
		
		if ( $actCabeceraCompra < 1 ){
			return sprintf ("{success: false , reason: 'No se modifico el subtotal e iva de la compra', details: '%s'}",$out);
		}else{		
			return sprintf ("{ success : true , reason :'Compra modificada completamente' , details:'%s'}",$out);
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
				
				
			return sprintf ("{success : true , reason :'Producto eliminado completamente de la compra %s'}",$out);
			
			
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
	return sprintf ("{success : true , reason :'El producto cambio de cantidad %s'}",$out);
			
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

	$sucursal = $_SESSION['sucursal'];
	//$sucursal = 2;
			
	$compras = new Compras();
	$compras->setIdProveedor( $id_proveedor );
	$compras->setIdSucursal( $sucursal );
	$compras_sucursal = ComprasDAO::search($compras);
	$foo="";
	if( count($compras_sucursal) > 0 ){
		$sc = SucursalDAO::getByPK( $sucursal );
		$jsonArray = array();
		foreach($compras_sucursal as $compra){
			
			$usuario = UsuarioDAO::getByPK( $compra->getIdUsuario() );
			$total = $compra->getSubtotal() + $compra->getIva();
			$usuario = UsuarioDAO::getByPK( $compra->getIdUsuario() );
			
			$foo .= substr($compra,1,-2);
            
            $adeudo = 0;
            
            if($compra->getTipoCompra() == 'credito')
            {
                
                $total_pagos = 0;
                $purchase_payments = new PagosCompra();
			    $purchase_payments->setIdCompra( $compra->getIdCompra() );
			    $pc = PagosCompraDAO::search( $purchase_payments );

			    if ( count($pc) > 0 ){
				    foreach( $pc as $pago )
				    {
					    $total_pagos += $pago->getMonto();
				    }
			    }
                
                $totCompra = $compra->getSubtotal() + $compra->getIva();
			    $adeudo = $totCompra - $total_pagos;
                
            }
            
			$foo.=',"total":"'.$total.'","nombre":"'.$usuario->getNombre().'","descripcion":"'.$sc->getDescripcion().'","adeudo":"'.$adeudo.'"},';
						
		}//fin for
		$foo = substr($foo,0,-1);
		return " { success : true, datos : [".$foo."] }";
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

	$detalles = new DetalleCompra();
	$detalles->setIdCompra( $id_compra );
	
	$detalles_compra = DetalleCompraDAO::search( $detalles );
	$out = "";
	
	if( count($detalles_compra) > 0 ){
		
		foreach( $detalles_compra as $detail ){
			$inventario = new Inventario();
			$inventario->setIdProducto( $detail->getIdProducto() );
			$producto = InventarioDAO::search( $inventario );
			$subtot = $detail->getCantidad() * $detail->getPrecio();
			$out .= substr($detail,1,-2);
			$out .=',"denominacion":"'.$producto[0]->getDenominacion().'","subtotal":"'.$subtot.'"},';
		}//fin foreach
		$out = substr($out,0,-1);
		return " { success : true, datos : [".$out."]}";
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
	$pagos = new PagosCompra();
	$pagos->setIdCompra( $id_compra );
	
	$pagos_compra = PagosCompraDAO::search( $pagos );
	$out ="";
	
	if(count($pagos_compra)>0){
		foreach( $pagos_compra as $pago ){
			$out.= substr( $pago, 1, -1 );//[{jgkjgk}] -> {jgkjgk}
			$out.=","; //{jgkjgk} -> {jgkjgk},
		}
		$data ="[";
		$data .= substr($out,0,-1)."]"; // [{jgkjgk},{jgkjgk},{jgkjgk},] -> [{jgkjgk},{jgkjgk},{jgkjgk}]
		return "{ success : true, datos : ". $data."}";
	}else{
		return "{ success : false , reason: 'Esta compra no tiene abonos'}";
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

	$out = ""; 
	$sucursal= $_SESSION['sucursal'];
	//$sucursal= 2;
	
	$compra = new Compras();
	$compra->setIdProveedor( $id_proveedor );
	$compra->setIdSucursal( $sucursal );	
	$compra->setTipoCompra( 'credito' );	
	$compras = ComprasDAO::search( $compra );
	
	if( count($compras) > 0 ){
		$jsonArray = array();
		foreach( $compras as $c )
		{
			$total_pagos = 0;
			$purchase_payments = new PagosCompra();
			$purchase_payments->setIdCompra( $c->getIdCompra() );
			$pc = PagosCompraDAO::search( $purchase_payments );

			if ( count($pc) > 0 ){
				foreach( $pc as $pago )
				{
					$total_pagos += $pago->getMonto();
				}
			}
			$proveedor = ProveedorDAO::getByPK( $c->getIdProveedor() );
			$totCompra = $c->getSubtotal() + $c->getIva();
			$adeudo = $totCompra - $total_pagos;
			$usuario = UsuarioDAO::getByPK( $c->getIdUsuario() );
			$sc = SucursalDAO::getByPK( $c->getIdSucursal() );
			
			$out .= substr($c,1,-2);

			$out.=',"total":"'.$totCompra.'","abonado":"'.$total_pagos.'","adeudo":"'.$adeudo.'","nombre":"'.$proveedor->getNombre().'","comprador":"'.$usuario->getNombre().'","sucursal":"'.$sc->getDescripcion().'"},';
	
		}//fin foreach compras
		$out = substr($out,0,-1);
		return " { success : true, datos : [".$out."] }";
	}else{
		return " { success : false , reason: 'No se le han hecho compras a credito a este proveedor'}";
	}
	
}//fin purchase_payments


/**
 * existenciaProductoSucursal
 * itemExistence_sucursal
 *
 * Regresa las existencias de un producto en una sucursal, 
 * recibiendo el id del producto y el de la sucursal lo saca de la session
 *
 * @param <type> $id_producto
 */
function itemExistence_sucursal( $id_producto, $id_proveedor ){
	$sucursal= $_SESSION['sucursal'];
	//$sucursal= 2;
	
	$producto = DetalleInventarioDAO::getByPK( $id_producto, $sucursal );
	$inventario = InventarioDAO::getByPK( $id_producto );
				  
	$productoProv = new ProductosProveedor();
	//$productoProv->setIdProducto( $id_producto );
	$productoProv->setIdInventario( $id_producto );
	$productoProv->setIdProveedor( $id_proveedor );
	
	$producto_proveedor = ProductosProveedorDAO::search( $productoProv );
	
	if( count($producto_proveedor) > 0 ){
		$jsonArray = array();
		
		array_push( $jsonArray, array("id_producto"=>$inventario->getIdProducto(), "nombre"=>$inventario->getNombre(), "denominacion"=>$inventario->getDenominacion(), "precio_venta"=>$producto->getPrecioVenta(), "existencias"=>$producto->getExistencias(), "precio"=>$producto_proveedor[0]->getPrecio() ) );
		
		return " { success : true, datos : ". json_encode($jsonArray)."}";
		
	}else{
		return "{success: false, reason:'Este producto no esta disponible' }";
	}
}


/**
 * list_provider_products
 * 
 * Dado el id del proveedor regresa los productos que éste surte a esta
 * sucursal
 *
 * @param <type> $id_provider
 */
function list_provider_products( $id_provider ){
	
	$sucursal = $_SESSION['sucursal'];
	
	$productosSucursal = new DetalleInventario();
	$productosSucursal->setIdSucursal( $sucursal );
	
	$productos = DetalleInventarioDAO::search( $productosSucursal );
	
	$prodProv = new ProductosProveedor();
	$prodProv->setIdProveedor( $id_provider );
	
	$productosProveedor = ProductosProveedorDAO::search( $prodProv );
	
	$numProd = count( $productos );
	
	$numProdProv = count( $productosProveedor );
	
	$datos ="";
	$numProductosProv = 0;
	
	for( $i = 0; $i < $numProdProv; $i++ ){
		
		for( $j = 0; $j < $numProd; $j++ ){
			
			if( $productos[ $j ]->getIdProducto() == $productosProveedor[ $i ]->getIdInventario()  ){

				$inventario = InventarioDAO::getByPK( $productos[ $j ]->getIdProducto() );
				
				$datos .= '{ "id_producto":"'.$productos[ $j ]->getIdProducto().'","nombre":"'.$inventario->getNombre().'","denominacion":"'.$inventario->getDenominacion().'","precio_venta":"'.$productos[ $j ]->getPrecioVenta().'","existencias":"'.$productos[ $j ]->getExistencias().'","precio":"'.$productosProveedor[ $i ]->getPrecio().'" },';
				
				$numProductosProv++;
				
			}//fin if
			
		}//fin for j		
	}//fin for i
	
	if( $numProductosProv < 1){
		return "{success:false , reason: 'Este proveedor no surte a esta sucursal con ningun producto'}";
	}
	
	$out = substr($datos,0,-1);
	
	return '{ success:true , datos:['.$out.'] ,details:" numero de productos en esta suc '.$numProd.' y num de prod q vende este prov '.$numProdProv.'"}';
}//fin metodo list_provider_productos


switch ($args['action']) {
    case '1201':
        $jsonItems = $args['jsonItems'];
        $id_proveedor = $args['id_proveedor'];
        $tipo_compra = $args['tipo_compra'];
        $modo_compra = $args['modo_compra'];
		unset($args);
		
        $ans = insert_purchase($jsonItems, $id_proveedor, $tipo_compra, $modo_compra);
        echo $ans;
	break;
	
	case '1202':
		$id_compra = $args['id_compra'];
        unset($args);
        $ans = delete_purchase($id_compra);
        echo $ans;
	break;
	
	case '1203':
		$jsonItems = $args['jsonItems'];
        $id_compra = $args['id_compra'];
		$id_proveedor = $args['id_proveedor'];
		$tipo_compra = $args['tipo_compra'];
        $modo_compra = $args['modo_compra'];
        unset($args);
        $ans = addItems_Existent_purchase($jsonItems,$id_compra, $id_proveedor, $tipo_compra, $modo_compra);
        echo $ans;
	break;
	
	case '1204':
		$jsonItems = $args['jsonItems'];
        unset($args);
        $ans = removeItem_Existent_purchase($jsonItems);
        echo $ans;
	break;
	
	case '1205':
		$id_compra = $args['id_compra'];
		$id_producto = $args['id_producto'];
		$precio = $args['precio'];
        $cantidad = $args['cantidad'];
        unset($args);
        $ans = EditItem_Existent_purchase( $id_compra, $id_producto, $precio, $cantidad );
        echo $ans;
	break;
	
	case '1206':
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		
		$ans= list_sucursal_purchases( $id_proveedor );
		echo $ans;
	break;
	
	case '1207':
		$id_compra = $args['id_compra'];
		unset($args);
		
		$ans= purchase_details( $id_compra );
		echo $ans;
	break;
	
	case '1208':
		$id_compra = $args['id_compra'];
		unset($args);
		
		$ans= purchase_payments( $id_compra );
		echo $ans;
	break;
	
	case '1209':
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		
		$ans= credit_providerPurchases( $id_proveedor );
		echo $ans;
	break;
	
	case '1210':
		$id_producto = $args['id_producto'];
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		
		$ans= itemExistence_sucursal( $id_producto, $id_proveedor );
		echo $ans;
	break;
	
	case '1211':
		$id_proveedor = $args['id_proveedor'];
		unset($args);
		$ans = list_provider_products( $id_proveedor );
		echo $ans;
	break;
	
	case '1220':
        $jsonItems = $args['jsonItems'];
 		unset($args);
		include_once("productos_proveedor.controller.php");
        $ans = insert_provider_product( $jsonItems );
        echo $ans;
	break;
	
	case '1221':
		$id_proveedor = $args['id_proveedor'];
        unset($args);
		include_once("productos_proveedor.controller.php");		
        $ans = list_provider_available_products( $id_proveedor );
        echo $ans;
	break;
}//end switch
?>
