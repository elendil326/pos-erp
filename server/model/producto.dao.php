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
		$sql = "select * from producto where ( nombre_producto like ? or codigo_producto like ? ) and activo = 1 limit ?; ";

		$val = array( "%" . $query . "%" , "%" . $query . "%" , $how_many );
		
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array( );
		foreach ($rs as $foo) {
			$bar =  new Producto($foo);
    		array_push( $ar,$bar);
		}
		return $ar;
	}
	

}
