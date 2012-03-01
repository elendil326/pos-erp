<?php

require_once ('Estructura.php');
require_once("base/clasificacion_producto.dao.base.php");
require_once("base/clasificacion_producto.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** ClasificacionProducto Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ClasificacionProducto }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class ClasificacionProductoDAO extends ClasificacionProductoDAOBase
{
	public static function buscarQuery($query, $how_many = 100){
		$sql = "select * from clasificacion_producto where ( nombre like ? or descripcion like ? )  limit ?; ";

		$val = array( "%" . $query . "%" , "%" . $query . "%" , $how_many );
		
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array( );
		foreach ($rs as $foo) {
			$bar =  new ClasificacionProducto($foo);
    		array_push( $ar,$bar);
		}
		return $ar;
	}
}
