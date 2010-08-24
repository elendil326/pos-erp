<?php
/** Facturas ventas Controller
  * 
  * Este script contiene las funciones necesarias para realizar las diferentes operaciones 
  * que se pueden hacer al dar de alta una factura para un cliente
  * @author Juan Manuel Hernadez <manuel@caffeina.mx> 
  * 
  */

/**
 * Se importan los DAO para poder realizar las operaciones sobre la BD.
 */ 
require_once('../server/model/ventas.dao.php');
require_once('../server/model/detalle_venta.dao.php');
require_once('../server/model/pagos_venta.dao.php');
require_once('../server/model/factura_venta.dao.php');
require_once('../server/model/sucursal.dao.php');
require_once('../server/model/impuesto.dao.php');
/**
 * facturar_sale
 *
 * Funcion que realiza los movimientos necesarios para dar de alta una factura.
 * Al dar de alta una factura la venta se divide en 2 ventas, la venta que contiene los productos
 * no facturados y otra venta con los productos facturados, si la venta es de contado
 * no se debe de hacer nada mas, por lo contrario si la venta es a credito se deben de regularizar
 * los pagos, es decir deben cuadrar con los subtatoles que hayan quedado tanto para la venta con 
 * productos facturados como para los no facturados, y al final de esto eliminar la venta inicial.
 * El metodo deduce dado un subtotal de cada venta lo que corresponderia al iva y al subtotal
 *
 * @param <type> $id_venta
 * @param <type> $jsonItems
 * @param <type> $todos
 */

function facturar_sale( $id_venta , $jsonItems, $todos ) {
	
	$sucursal = $_SESSION['sucursal'];
	
	$folioFactura = generarFolio( $sucursal );
	
	if( $todos == 'true' ){
			
		$factura = new FacturaVenta();
		$factura->setFolio( $folioFactura );
		$factura->setIdVenta( $id_venta );
		$factura->setIdSucursal( $sucursal );
		
		$ans = FacturaVentaDAO::save( $factura );
		
		if( $ans > 0 ){
			return "{success: true, reason:'Factura registrada correctamente, se facturaron todos los elementos de la venta'}";
		}else{
			return "{success: false, reason:'No se pudo registrar la factura de esta venta, Se ivan a registrar todos los elementos de la venta'}";
		}
	}else{//if else todos = true (aki todos = false)
		
		$venta = VentasDAO::getByPK( $id_venta );
		
		if( !is_object($venta) ){
			return "{success: false, reason:'No existe esta venta'}";
		}
		
		$arregloItems = json_decode($jsonItems,true);
		
		$dim = count($arregloItems);
		$ban = false; //revisara que hay por lo menos 1 producto para facturar

		$detalle_nuevaVenta = array();
		$detalle_nuevaVentaFacturada = array();
		$out="";
		for ($j = 0; $j < $dim; $j++){
			if( $arregloItems[$j]['facturar'] == true ){
				$ban = true;
				$out = " si entre al for por q hay algo q facturar ban vale -> ".$ban;
				break;
			}
		}//fin for que ve si hay productos para facturar
		
		if( $ban == false ){
			return "{success: false, reason:'No hay productos que facturar de esta venta'}";
		}
		
		$v1 = new Ventas();
		$v2 = new Ventas();
		
		$v1->setIdCliente( $venta->getIdCliente() );
		$v1->setTipoVenta( $venta->getTipoVenta() );
		$v1->setFecha( $venta->getFecha() );
		$v1->setSubtotal( 0 );
		$v1->setIva( 0 );
		$v1->setDescuento( $venta->getDescuento() );
		$v1->setTotal( 0 );
		$v1->setIdSucursal( $venta->getIdSucursal() );
		$v1->setIdUsuario( $venta->getIdUsuario() );
		$v1->setPagado( 0 );
		$v1->setIp( $venta->getIp() );
		
		$v2->setIdCliente( $venta->getIdCliente() );
		$v2->setTipoVenta( $venta->getTipoVenta() );
		$v2->setFecha( $venta->getFecha() );
		$v2->setSubtotal( 0 );
		$v2->setIva( 0 );
		$v2->setDescuento( $venta->getDescuento() );
		$v2->setTotal( 0 );
		$v2->setIdSucursal( $venta->getIdSucursal() );
		$v2->setIdUsuario( $venta->getIdUsuario() );
		$v2->setPagado( 0 );
		$v2->setIp( $venta->getIp() );
		
		//return "{succees:false, reason:'Apenas voy a guardar las 2 ventas , ".$out." , fuera del for ban bale -> ".$ban."' }";
		$r1 = VentasDAO::save( $v1 );
		$r2 = VentasDAO::save( $v2 );
		
		if( $r1 < 1 || $r2 < 1 ){
			VentasDAO::delete( $v1 );
			VentasDAO::delete( $v2 );
			return "{success:false, reason:'No se hizo la division de la venta para poder facturar'}";
		}
		
		$totalV1 = 0;
		$totalV2 = 0;
		
		for( $i=0; $i < $dim; $i++ ){
			
			$item = DetalleVentaDAO::getByPK( $id_venta, $arregloItems[$i]['id'] );
			
			if( $arregloItems[$i]['facturar'] == 'true' ){
				
				$cantidadItemDB = $item->getCantidad();
				$cantidadNewItem = $arregloItems[$i]['cantidad'];
				
				if( $cantidadNewItem > $cantidadItemDB || $cantidadNewItem <= 0 ){
					return "{success:false , reason:'La cantidad del producto numero ".($i +1 ).", es incorrecta'}";
				}
				
				if( $cantidadNewItem < $cantidadItemDB ){
					
					$detalle1 = new DetalleVenta();
					$detalle1->setIdVenta( $v1->getIdVenta() );
					$detalle1->setIdProducto( $item->getIdProducto() );
					$detalle1->setCantidad( $cantidadNewItem );
					$detalle1->setPrecio( $item->getPrecio() );
					
					$totalV1 += $detalle1->getCantidad() * $detalle1->getPrecio();
					
					
					$detalle2 = new DetalleVenta();
					$detalle2->setIdVenta( $v2->getIdVenta() );
					$detalle2->setIdProducto( $item->getIdProducto() );
					$detalle2->setCantidad( $cantidadItemDB - $cantidadNewItem );
					$detalle2->setPrecio( $item->getPrecio() );
					
					$totalV2 += $detalle2->getCantidad() * $detalle2->getPrecio();
					
					$r1 = DetalleVentaDAO::save( $detalle1 );
					$r2 = DetalleVentaDAO::save( $detalle2 );
					
					if( $r1 < 1 || $r2 < 1 ){
						try{
							DetalleVentaDAO::delete( $detalle1 );
						}catch(Exception $e) {}
						
						try{
							DetalleVentaDAO::delete( $detalle2 );
						}catch(Exception $e) {}
						
						return "{success:false, reason:'No se genero la factura debido al producto numero ".($i+1)."'}";
					}
					
					array_push( $detalle_nuevaVentaFacturada, $detalle1 );
					array_push( $detalle_nuevaVenta, $detalle2 );
					
				}//fin if $cantidadNewItem < $cantidadItemDB
				
				if( $cantidadNewItem == $cantidadItemDB ){
					
					$detalle1 = new DetalleVenta();
					$detalle1->setIdVenta( $v1->getIdVenta() );
					$detalle1->setIdProducto( $item->getIdProducto() );
					$detalle1->setCantidad( $cantidadNewItem );
					$detalle1->setPrecio( $item->getPrecio() );
					
					$totalV1 += $detalle1->getCantidad() * $detalle1->getPrecio();
					
					$ans = DetalleVentaDAO::save( $detalle1 );
					
					if( $ans < 1 ){
						return "{success:false, reason:'No se genero la factura debido al producto numero ".($i+1)."'}";
					}
					
					array_push( $detalle_nuevaVentaFacturada, $detalle1 );
					
				}//fin if $cantidadNewItem < $cantidadItemDB
				
			}else{//if else arregloItems[$i]['facturar']=true  (aki entra cuando facturar = false)
				
				$detalle2 = DetalleVentaDAO::getByPK( $id_venta, $arregloItems[$i]['id'] );
				$detalle2->setIdVenta( $v2->getIdVenta() );
				$ans = DetalleVentaDAO::save( $detalle2 );
				
				if( $ans < 1 ){
					return "{success:false, reason:'No se genero la factura debido al producto numero ".($i+1)."'}";
				}
				
				$totalV2 += $detalle2->getCantidad() * $detalle2->getPrecio();
				array_push( $detalle_nuevaVenta, $detalle2 );
				
			}//fin else facturar = false
			
		}//fin for que recorre los items de la venta a facturar
		
		$valorIva = 0;
		$imp = new Impuesto();
		$imp->setIdSucursal( $venta->getIdSucursal() );
		$impuesto = ImpuestoDAO::search( $imp );
		
		if ( count($impuesto) > 0 ){//encontro un impuesto
			$valorIva = $impuesto[0]->getValor() / 100;
		}
		
		$ivaV1 = $totalV1 * $valorIva;
		$subtotV1 = $totalV1 - $ivaV1;
		
		$v1->setIva( $ivaV1 ); 
		$v1->setSubtotal( $subtotV1 );
		$v1->setTotal( $totalV1 );
		$v1->setPagado( $totalV1 );
		
		$ivaV2 = $totalV2 * $valorIva;
		$subtotV2 = $totalV2 - $ivaV2;
		
		$v2->setIva( $ivaV2 ); 
		$v2->setSubtotal( $subtotV2 );
		$v2->setTotal( $totalV2 );
		$v2->setPagado( $totalV2 );
		
		$r1 = VentasDAO::save( $v1 );
		$r2 = VentasDAO::save( $v2 );
		
		$out="";
		
		if( $r1 < 1 ){
			$out .= ", No se actualizo la cabecera de la venta facturada";
		}
		if( $r2 < 1 ){
			$out .= ", No se actualizo la cabecera de la venta con los productos no facturados";
		}
		$out .= "Antes del if de entrar a payments regularization tipoVenta -> ".$venta->getTipoVenta()." todos -> ".$todos." ";
		if( $venta->getTipoVenta() == 'credito' && $todos == 'false'  ){//regularizar pagos solo si es a credito y no se van a facturar todos los productos de la venta, es decir solo algunos
			$out .= payments_regularization( $id_venta , $v1 , $v2 );
		}
		
		
		$factura = new FacturaVenta();
		$factura->setFolio( $folioFactura );
		$factura->setIdVenta( $v1->getIdVenta() );
		$factura->setIdSucursal( $sucursal );
		$ans = FacturaVentaDAO::save( $factura );
		
		
		if ( $ans < 1 ){
			$out .= ", No se inserto la venta a facturar en la tabla de factura_venta";
		}
		
		/* 
			se elimina la venta original y sus detalles 
		*/
		$numDetB = 0;
		$detallesDelete = new DetalleVenta();
		$detallesDelete->setIdVenta( $id_venta );
		
		$detallesEliminar = DetalleVentaDAO::search( $detallesDelete );
		
		foreach( $detallesEliminar as $detailD ){
			
			$borrarDet = new DetalleVenta();
			$borrarDet->setIdVenta( $id_venta );
			$borrarDet->setIdProducto( $detailD->getIdProducto() );
			
			$resp = DetalleVentaDAO::delete( $borrarDet );
			if( $resp > 0 ){
				$numDetB++;
			}
		}
		$out .=" , SE BORRARON ".$numDetB." DETALLES DE LA VENTA ORIGINAL";
		
		$ans = VentasDAO::delete( $venta );
		
		if ( $ans < 1 ){
			$out .= ", No se elimino la venta original de la cual se derivaron las 2 ventas nuevas";
		}
		
		return "{success: true, reason:'Factura registrada correctamente, se facturaron ALGUNOS elementos de la venta', details:'".$out."' }";
		
	}//fin else todos (true = false)
}//fin  facturar_sale

/**
 * generarFolio
 *
 * Funcion que genera el folio siguiente para una factura de acuerdo a la sucursal 
 * de donde será expedida dicha factura, Se toma el campo Letras factura de la tabla 
 * sucursal para saber como sera la nomenclatura de la factura, y se extrae la cifra
 * en que va la numeracion de facturas de esa sucursal para generar el numero que le sigue
 *
 * @param <type> $sucursal
 */
function generarFolio( $sucursal ){
	
	$sc = SucursalDAO::getByPK( $sucursal );
	$letrasFolio = $sc->getLetrasFactura();
	
	$factura = new FacturaVenta();
	$factura->setIdSucursal( $sucursal );
	
	$facturasSucursal = FacturaVentaDAO::search( $factura );
	$dim = count($facturasSucursal);
	
	if( $dim < 1 ){
		 return "".$letrasFolio."1 count vale: ".$dim;//es la primera factura, en la factura impresa será A0001 y en la Bd sera A1
	}else{
		
		$letrasLength = strlen( $letrasFolio );//por si las letras son A- ó A-1-
		
		$num = substr($facturasSucursal[ $dim -1 ]->getFolio(), $letrasLength ); // se toma la ultima factura y se le saca el numero de folio con un sbstr, A0001 -> 0001
		$n = $num + 1;
		
		return "".$letrasFolio.$n;
	}
}


/**
 * payments_regularization
 *
 * Funcion que regulariza los pagos de una venta despues de divir 1 en 2, equilibra
 * los pagos para que cuadren con los dos nuevos totales obtenido tras la generacion de 
 * esas 2 ventas para emitir una factura.
 *
 * @param <type> $id_venta
 * @param <type> $venta1
 * @param <type> $venta2
 */
function payments_regularization( $id_venta , $venta1 , $venta2 ) {
	$out =" 	,entre a payments regularization, ";
	$pagos = new PagosVenta();
	$pagos->setIdVenta( $id_venta );
	
	$pagosVenta = PagosVentaDAO::search( $pagos ); //este mismo se usa al final para borrar esos pagos
	
	$totalV1 = $venta1->getTotal();
	$out .= " el total de la venta 1 es de: ".$totalV1." ";
	$pagosV1 = array();
	$pagosV2 = array();
	
	$abonado = 0;
	$ban = true;

	foreach( $pagosVenta as $pago ){
		
		$abonado += $pago->getMonto();
		
		$aux = $totalV1 - $abonado;
		
		if( $aux < 0 && $ban == true ){
			
			$aux2 = abs( $aux );
			$pv1 = $pago->getMonto() - $aux2;
			if( $pv1 > 0 ){
				array_push( $pagosV1 , array( "id_venta"=>$venta1->getIdVenta(), "fecha"=>$pago->getFecha(), "monto"=>$pv1 ) );	
			}
			
			array_push( $pagosV2 , array( "id_venta"=>$venta2->getIdVenta(), "fecha"=>$pago->getFecha(), "monto"=>$aux2 ) );	
			$ban = false;
			
		}else{
			if( $abonado <= $totalV1 ){
				array_push( $pagosV1 , array( "id_venta"=>$venta1->getIdVenta(), "fecha"=>$pago->getFecha(), "monto"=>$pago->getMonto() ) );
			}else{
				array_push( $pagosV2 , array( "id_venta"=>$venta2->getIdVenta(), "fecha"=>$pago->getFecha(), "monto"=>$pago->getMonto() ) );
			}
			
			
		}//else aux <  0 ban=tru (entra aki y ban = false ) 
		
	}//fin foreach

	$numPv1 = count( $pagosV1 );
	$numPv2 = count( $pagosV2 );
	
	for( $i=0; $i < $numPv1; $i++){
		
		$pagosven = new PagosVenta();
		$pagosven->setIdVenta( $pagosV1[$i]['id_venta'] );
		$pagosven->setFecha( $pagosV1[$i]['fecha'] );
		$pagosven->setMonto( $pagosV1[$i]['monto'] );
		
		$ans = PagosVentaDAO::save( $pagosven );
		if( $ans < 1 ){
			$out .=", No se regularizo un pago para la venta ".$venta1->getIdVenta()." de un monto de: ".$pagosV1[$i]['monto']."";
		}
	}
	
	for( $i=0; $i < $numPv2; $i++){
		
		$pagosven = new PagosVenta();
		$pagosven->setIdVenta( $pagosV2[$i]['id_venta'] );
		$pagosven->setFecha( $pagosV2[$i]['fecha'] );
		$pagosven->setMonto( $pagosV2[$i]['monto'] );
		
		$ans = PagosVentaDAO::save( $pagosven );
		
		if( $ans < 1 ){
			$out .=", No se regularizo un pago para la venta ".$venta2->getIdVenta()." de un monto de: ".$pagosV2[$i]['monto']."";
		}
	}
	
	/*
		borrar pagos de la venta a borrar
	*/
	$numPagosDel = 0;	
	foreach( $pagosVenta as $payment ){
		$paymentD = new PagosVenta();
		
		$paymentD->setIdPago( $payment->getIdPago() );
		$resul = PagosVentaDAO::delete( $paymentD );
		if( $resul > 0 ){
			$numPagosDel++;
		}
	}//fin foreach
	$out .= " , SE BORRARON ".$numPagosDel." PAGOS de la venta ".$id_venta ." ORGINAL ";
	return $out;
}//fin payments_regularization



switch($args['action'])
{
	case '1801':
        $id_venta = $args['id_venta'];
        $jsonItems = $args['jsonItems'];
        $todos = $args['todos'];
        unset($args);
		$ans = facturar_sale( $id_venta , $jsonItems, $todos );
        echo $ans;
	break;
	
}
?>