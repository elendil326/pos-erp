<?php

require_once ('Estructura.php');
require_once("base/producto.dao.base.php");
require_once("base/producto.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Producto Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Producto }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class ProductoDAO extends ProductoDAOBase
{

	public static function buscarProductos($query, $how_many = 100){
		
		
		/*$sql = "select * from producto where ( nombre_producto like ? or codigo_producto like ? ) and activo = 1 limit ?; ";
		$val = array( "%" . $query . "%" , "%" . $query . "%" , $how_many );*/
		
		
		
		$parts = explode(" ", $query);

		$sql = "select * from producto where (";
		$val = array();
		$first = true;
		foreach ($parts as $p) {
			if($first){
				$first = false;
				
			}else{
				$sql .= " and ";
			}
			$sql .= "  nombre_producto like ? ";
			array_push($val , "%" . $p . "%");
		}
		
		$sql .= " or codigo_producto like ? ) limit 20 ";
		array_push($val , "%" . $query . "%");
		
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array( );
		foreach ($rs as $foo) {
			$bar =  new Producto($foo);
    		array_push( $ar,$bar);
		}
		return $ar;
	}
  




	public static function buscarProductoEnSucursal($id_producto, $id_sucursal){
			$sql = "SELECT
				lp.cantidad,
				lp.id_unidad,
				lp.id_lote,
				l.folio
			FROM 
				lote_producto lp,
				lote l,
				sucursal s,
				almacen a
			WHERE 
				lp.id_producto = ?
				and lp.id_lote = l.id_lote
				and l.id_almacen = a.id_almacen
				and a.id_sucursal = ?";
				
			global $conn;
			$rs = $conn->Execute($sql, array($id_producto, $id_sucursal));
			$ar = array( );
			foreach ($rs as $foo) {
	    		array_push( $ar, $foo);
			}
			return $ar;
	}
}
