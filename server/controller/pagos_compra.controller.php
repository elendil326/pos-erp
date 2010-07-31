<?php
/** Pagos Compra Controller
  * 
  * Este script contiene las funciones necesarias para realizar las diferentes operaciones 
  * que se pueden hacer durante un pago para una compra hecha a un proveedor, abonar,
  * y eliminar pagos.
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
 * insert_payment
 *
 * Funcion que inserta un nuevo abono a una compra, verifica que el monto que entra no rebace
 * la cantidad que resta por pagar
 * 
 *
 * @param <type> $id_compra
 * @param <type> $monto
 */

		
function insert_payment($id_compra , $monto) {
	
	if(!is_numeric($id_compra)){
		return "{success: false , reason:'La raferencia de la compra es erronea'}";
	}
	if( $monto < 1 ){
		return "{success: false , reason:'Debe abonar una cantidad correcta o mayor a 0'}";
	}
	
	
	$compra = ComprasDAO::getByPK( $id_compra );
	
	if( is_object($compra) && $compra->getTipoCompra() == 'credito' ){
		
		$total_pagos = 0;
		$pc = PagosCompraDAO::getByPK( $id_compra );
			
		if ( count($pc) > 0 ){
			foreach( $pc as $pago )
			{
				$total_pagos += $pago->getMonto();
			}
		}
		
		$totCompra = $compra->getSubtotal() + $compra->getIva();
		$adeudo = $totCompra - $total_pagos; 
			
		if( $monto > $adeudo ){//se trata de abonar mas de lo que debe
			return "{ success: false, reason: 'No se puede abonar mas de lo que debe a esta compra' }";
		}else{
			$pago = new PagosCompra();
			$pago->setIdCompra( $id_compra );
			$pago->setMonto( $monto );
			
			$ans = PagosCompraDAO::save( $pago );
			if( $ans > 0 ){
				return "{ success: true, reason:'Pago insertado correctamente' }";
			}else{
				return "{ success: false, reason:'No se inserto el pago de esta compra'}";
			}
		}
		
	}else{
		return "{ success: false, reason:'Esta compra no es a credito o no existe' }";
	}
}//fin insert_payment

function delete_payment($id_pago) {
	
	$pago = PagosCompraDAO::getByPK( $id_pago );
	
	if( is_object($pago) ){
		
		$ans = PagosCompraDAO::delete( $pago );
		
		if( $ans > 0 ){
			return "{ success: true, reason:'El pago se elimino correctamente'}";
		}else{
			return "{ success: false, reason: 'No se pudo eliminar el pago de esta compra'}";
		}
		
	}else{
		return "{ success: false, reason:'Este pago no existe' }";
	}
}//fin insert_payment
?>