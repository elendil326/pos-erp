<?php

require_once("base/compras.dao.base.php");
require_once("base/compras.vo.base.php");
require_once ('Estructura.php');
/** Compras Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Compras }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ComprasDAO extends ComprasDAOBase
{
 
 /**
 * list_purchases
 *
 * Funcion que regresa las compras hechas a un proveedor en especifico dentro
 * de un arreglo asociativo.
 *
 * @param <type> $id_proveedor
 * @param <type> $sucursal
 */
static function list_purchases( $id_proveedor , $sucursal ) {
	$query = "SELECT compras.id_compra, compras.id_proveedor, compras.tipo_compra, compras.fecha, compras.subtotal,compras.iva, sucursal.descripcion, usuario.nombre, (iva + subtotal) AS total FROM  `compras`  INNER JOIN  `usuario` ON compras.id_usuario = usuario.id_usuario INNER JOIN  `sucursal` ON compras.sucursal = sucursal.id_sucursal WHERE compras.id_proveedor = ? and compras.sucursal =?;");
	
	$params = array();
	array_push($params, $id_proveedor, $sucursal);
	
	global $conn;
    
	try{
		$rs = $conn->Execute($query,$params);
	}catch(Exception $e){
		$logger->log($e->getMessage(), PEAR_LOG_ERR);
	}
                
    
	$allData = array();
   	while(!$rs->EOF)
    {
     	$tupla = $rs->GetRowAssoc($toUpper=false);
		foreach($tupla as $atributo => &$valor ) $valor=utf8_encode($valor);
		array_push($allData,$tupla);
		$rs->MoveNext();
	}

    return $allData;
}//fin list_purchasess


/**
 * get_purchase_details
 *
 * Devuelve los productos que corresponden a una compra asi como su precio y cantidad dentro
 * de un arreglo asociativo.
 *
 * @param <type> $id_compra
 */
static function get_purchase_details( $id_compra ) {
	$query = "SELECT detalle_compra.id_compra ,inventario.denominacion, cantidad,precio,(cantidad * precio) as subtotal FROM `detalle_compra` inner join `inventario` on detalle_compra.id_producto = inventario.id_producto  where id_compra=?;";
	$params=array($id_compra);
	
	global $conn;
    
	try{
		$rs = $conn->Execute($query,$params);
	}catch(Exception $e){
		$logger->log($e->getMessage(), PEAR_LOG_ERR);
	}
                
    
	$allData = array();
   	while(!$rs->EOF)
    {
     	$tupla = $rs->GetRowAssoc($toUpper=false);
		foreach($tupla as $atributo => &$valor ) $valor=utf8_encode($valor);
		array_push($allData,$tupla);
		$rs->MoveNext();
	}

    return $allData;
}//fin get_purchase_details


/**
 * get_purchase_payments
 *
 * Devuelve los pagos (abonos) hechos a una venta a credito, devuelve los pagos en
 * un arreglo asociativo.
 *
 * @param <type> $id_compra
 */
static function get_purchase_payments( $id_compra ) {
	$query = "SELECT id_compra,fecha,monto FROM pagos_compra WHERE id_compra =?;";
	$params=array($id_compra);
	
	global $conn;
    
	try{
		$rs = $conn->Execute($query,$params);
	}catch(Exception $e){
		$logger->log($e->getMessage(), PEAR_LOG_ERR);
	}
                
    
	$allData = array();
   	while(!$rs->EOF)
    {
     	$tupla = $rs->GetRowAssoc($toUpper=false);
		foreach($tupla as $atributo => &$valor ) $valor=utf8_encode($valor);
		array_push($allData,$tupla);
		$rs->MoveNext();
	}

    return $allData;
}//fin get_purchase_payments

/**
 * get_credit_providerpurchases
 * Obtiene las compras a credito que se le han hecho a un proveedor asi como cuanto
 * se le ha abonado a cada una de ellas
 */
static function get_credit_providerpurchases( $id_proveedor ){
	$query = "SELECT c.id_compra, ( c.subtotal + c.iva ) AS  'total', IF( SUM( pv.monto ) >0, 
							SUM( pv.monto ) , 0 ) AS  'abonado', IF( (c.subtotal + c.iva - SUM( pv.monto ) ) >0, 
							(c.subtotal + c.iva - SUM( pv.monto ) ), (c.subtotal + c.iva ) ) AS  'adeudo', 
							pr.nombre AS  'nombre', DATE( c.fecha ) AS  'fecha', u.nombre AS comprador,
							sc.descripcion AS sucursal
							FROM  `pagos_compra` pv
							RIGHT JOIN compras c ON ( pv.id_compra = c.id_compra ) 
							INNER JOIN proveedor pr ON ( c.id_proveedor = pr.id_proveedor ) 
							INNER JOIN usuario u ON ( c.id_usuario = u.id_usuario ) 
							INNER JOIN sucursal sc ON ( c.id_sucursal = sc.id_sucursal ) 
							GROUP BY c.id_compra, pr.id_proveedor, c.fecha, c.tipo_compra, c.tipo_compra
							HAVING abonado < Total
							AND c.tipo_compra ='credito'
							AND pr.id_proveedor =?
							ORDER BY c.fecha;";
	$params = array($id_proveedor);
	
	global $conn;
    
	try{
		$rs = $conn->Execute($query,$params);
	}catch(Exception $e){
		$logger->log($e->getMessage(), PEAR_LOG_ERR);
	}
                
    
	$allData = array();
   	while(!$rs->EOF)
    {
     	$tupla = $rs->GetRowAssoc($toUpper=false);
		foreach($tupla as $atributo => &$valor ) $valor=utf8_encode($valor);
		array_push($allData,$tupla);
		$rs->MoveNext();
	}

    return $allData;
}//get_credit_providerpurchases
}
