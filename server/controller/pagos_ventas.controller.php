<?php
/** Pagos venta Controller
  * 
  * Este script contiene las funciones necesarias para realizar las diferentes operaciones 
  * que se pueden hacer durante un pago para una venta hecha a un cliente, abonar,
  * y eliminar pagos.
  * @author Juan Manuel Hernadez <manuel@caffeina.mx> 
  * 
  */

/**
 * Se importan los DAO para poder realizar las operaciones sobre la BD.
 */ 
require_once('../server/model/ventas.dao.php');
require_once('../server/model/detalle_venta.dao.php');
require_once('../server/model/pagos_venta.dao.php');

/**
 * insert_payment
 *
 * Funcion que inserta un nuevo abono a una venta, verifica que el monto que entra no rebace
 * la cantidad que resta por pagar
 * 
 *
 * @param <type> $id_venta
 * @param <type> $monto
 */

		
function insert_payment($id_venta , $monto) {
	
	if(!is_numeric($id_venta)){
		return "{success: false , reason:'La raferencia de la venta es erronea'}";
	}
	if( $monto < 1 ){
		return "{success: false , reason:'Debe abonar una cantidad correcta o mayor a 0'}";
	}
	
	
	$venta = VentasDAO::getByPK( $id_venta );
	
	if( is_object($venta) && $venta->getTipoVenta() == 'credito' ){
		
		$total_pagos = 0;
		$pv = PagosVentaDAO::getByPK( $id_venta );
			
		if ( count($pv) > 0 ){
			foreach( $pv as $pago )
			{
				$total_pagos += $pago->getMonto();
			}
		}
		
		$totVenta = $venta->getSubtotal() + $venta->getIva();
		$adeudo = $totVenta - $total_pagos; 
			
		if( $monto > $adeudo ){//se trata de abonar mas de lo que debe
			return "{ success: false, reason: 'No se puede abonar mas de lo que debe a esta venta' }";
		}else{
			$pago = new PagosVenta();
			$pago->setIdVenta( $id_venta );
			$pago->setMonto( $monto );
			
			$ans = PagosVentaDAO::save( $pago );
			if( $ans > 0 ){
				return "{ success: true, reason:'Pago insertado correctamente' }";
			}else{
				return "{ success: false, reason:'No se inserto el pago de esta venta'}";
			}
		}
		
	}else{
		return "{ success: false, reason:'Esta venta no es a credito o no existe' }";
	}
}//fin insert_payment

function delete_payment($id_pago) {
	
	$pago = PagosVentaDAO::getByPK( $id_pago );
	
	if( is_object($pago) ){
		
		$ans = PagosVentaDAO::delete( $pago );
		
		if( $ans > 0 ){
			return "{ success: true, reason:'El pago se elimino correctamente'}";
		}else{
			return "{ success: false, reason: 'No se pudo eliminar el pago de esta venta'}";
		}
		
	}else{
		return "{ success: false, reason:'Este pago no existe' }";
	}
}//fin insert_payment
?>