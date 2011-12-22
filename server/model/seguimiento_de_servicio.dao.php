<?php

require_once ('Estructura.php');
require_once("base/seguimiento_de_servicio.dao.base.php");
require_once("base/seguimiento_de_servicio.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** SeguimientoDeServicio Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link SeguimientoDeServicio }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class SeguimientoDeServicioDAO extends SeguimientoDeServicioDAOBase
{
	public static function seguimientosPorServicio( $id_servicio ){
		$sql = "select * from seguimiento_de_servicio where id_orden_de_servicio = ?; ";
		$val = array( $id_servicio );
		
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array();
		foreach ($rs as $foo) {
			$bar =  new SeguimientoDeServicio($foo);
    		array_push( $ar,$bar);
		}
		return $ar;
	}
}
