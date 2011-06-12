<?php

require_once ('Estructura.php');
require_once("base/factura_venta.dao.base.php");
require_once("base/factura_venta.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Alan Gonzalez
  * @package docs
  * 
  */
/** FacturaVenta Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link FacturaVenta }. 
  * @author Alan Gonzalez
  * @access public
  * @package docs
  * 
  */
class FacturaVentaDAO extends FacturaVentaDAOBase
{
	public static function obtenerVentasFacturadasDeCliente($id_cliente){
		$sql = "SELECT * FROM 
		ventas as v,
		factura_venta as fv
		WHERE
		v.id_cliente = ?
		and v.id_venta = fv.id_venta ";
		
		$val = array($id_cliente);

		global $conn;


		$rs = $conn->Execute($sql, $val);

		$res = array();
		
		foreach ($rs as $foo) {
			array_push( $res, $foo);
		}
		return $res;
	}
}
