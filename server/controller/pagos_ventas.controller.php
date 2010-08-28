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
		$out ="";
		$numPagos =0;
		$total_pagos = 0;
		
		$payment = new PagosVenta();
		$payment->setIdVenta( $id_venta );
		
		$pv = PagosVentaDAO::search( $payment );
			
		if ( count($pv) > 0 ){
			foreach( $pv as $pago )
			{
				$total_pagos += $pago->getMonto();
				$numPagos++;
			}
		}
		$out .= "Antes de insertar pago Esta venta tiene un total en pagos de: ".$total_pagos." y numero de pagos ".$numPagos;
		$totVenta = $venta->getSubtotal() + $venta->getIva();
		$adeudo = $totVenta - $total_pagos; 
			
		if( $monto > $adeudo ){//se trata de abonar mas de lo que debe
			return "{ success: false, reason: 'No se puede abonar mas de lo que debe a esta venta', details: '".$out."' }";
		}else{
			$pago = new PagosVenta();
			$pago->setIdVenta( $id_venta );
			$pago->setMonto( $monto );
			
			$ans = PagosVentaDAO::save( $pago );
			if( $ans > 0 ){
				$out2 ="";
				$venta->setPagado( ($total_pagos + $monto) );
				$ans2 = VentasDAO::save( $venta );
				if ( $ans2 < 1 ){
					$out2 .= ", NO SE ACUTALIZA LA TABLA VENTA EN EL CAMPO PAGADO";
				}
				return "{ success: true, reason:'Pago insertado correctamente".$out2."', details: '".$out."' }";
			}else{
				return "{ success: false, reason:'No se inserto el pago de esta venta', details:'".$out."'}";
			}
		}
		
	}else{
		return "{ success: false, reason:'Esta venta no es a credito o no existe' }";
	}
}//fin insert_payment

/**
 * delete_payment
 *
 * Funcion que elimina un abono de una venta y manda llamar la funcion q actualiza el campo
 * pagado de la tabla ventas
 * 
 *
 * @param <type> $id_pago
 */
function delete_payment($id_pago) {
	$out ="";
	$pago = PagosVentaDAO::getByPK( $id_pago );
	$id_venta = $pago->getIdVenta();
	
	if( is_object($pago) ){
		
		$ans = PagosVentaDAO::delete( $pago );
		
		if( $ans > 0 ){
			
			$res = update_pagadoField( $id_venta );
			
			if( $res < 1){
				$out .= ", No se actualizo el campo pagado de la tabla ventas";
			}
			
			return "{ success: true, reason:'El pago se elimino correctamente".$out."'}";
		}else{
			return "{ success: false, reason: 'No se pudo eliminar el pago de esta venta'}";
		}
		
	}else{
		return "{ success: false, reason:'Este pago no existe' }";
	}
}//fin insert_payment


/**
 * update_pagadoField
 *
 * Funcion que actualiza el campo pagado de la tabla ventas despues de
 * eliminar un pago
 * 
 *
 * @param <type> $id_venta
 */
function update_pagadoField( $id_venta ){
	
	$totalPagos = 0;
	$pagos = new PagosVenta();
	$pagos->setIdVenta( $id_venta );
	
	$pagosVenta = PagosVentaDAO::search( $pagos );
	
	foreach( $pagosVenta as $payment ){
		$totalPagos += $payment->getMonto();
	}
	
	$venta = VentasDAO::getByPK( $id_venta );
	$venta->setPagado( $totalPagos );
	
	return VentasDAO::save( $venta );
}

/**
*	sucursalAbonos
*
*	Obtiene los abonos que se han hecho a ventas a crédito
*	en determinada sucursal en cierto período de tiempo
*
*	@param <Integer> id_sucusal El id de la sucursal de la cual se quieren obtener los datos
*	@param <String> fechaInicio Fecha de inicio del periodo de tiempo que se quiere analizar. Debe de ir junto con fechaFinal
*	@param <String> fechaFinal Fecha final del periodo de tiempo que se quiere analizar. Debe de ir junto con fechaInicio
*/

function sucursalAbonos( $id_sucursal, $fechaInicio, $fechaFinal )
{

	$data = PagosVentaDao::abonosSucursal($id_sucursal, $fechaInicio, $fechaFinal);

	$result = '{ "success": true, "datos": '.json_encode($data[0]).'}';


	return $result;		

}


switch( $args['action'] )
{
	case 2401: 
		
		if ( !isset( $args['id_sucursal'] ) && !isset( $args['fecha-inicio'] ) && !isset( $args['fecha-final'] ) )
		{
			echo ' { "success": false, "error": "Faltan parametros" } ';
			return;
		}
		
		$id_sucursal = $args['id_sucursal'];
		$fechaInicio = $args['fecha-inicio'];
		$fechaFinal = $args['fecha-final'];
		
		
		echo sucursalAbonos( $id_sucursal, $fechaInicio, $fechaFinal );
		
		
	break;
}


?>