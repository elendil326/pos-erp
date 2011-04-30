<?php

require_once ('Estructura.php');
require_once("base/ventas.dao.base.php");
require_once("base/ventas.vo.base.php");

/** .
  * 
  * @author no author especified
  * @package docs
  * 
  */
/** Ventas Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Ventas }. 
  * @author no author especified
  * @access public
  * @package docs
  * 
  */
class VentasDAO extends VentasDAOBase
{
	/**
	  * Obtener un arreglo con las ultimas ventas por dia de los ultimos
	  * <i>$dias</i> dias.
	  **/
	public static function contarVentasPorDia($sucursal , $dias = 7){
		$sql = "select date_format(fecha,'%Y-%m-%d') as fecha, count(*) as ventas from ventas where id_sucursal = ? group by date_format(fecha,'%Y-%m-%d') order by fecha limit ?";
		
		global $conn;
		$val = array( $sucursal, $dias );
		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( $foo[0] => $foo[1] ) );
		}
		return $res;

	}
	
	public static function contarVentasPorMes($sucursal , $meses = 1){
		$sql = "select date_format(fecha,'%Y-%m') as fecha, count(*) as ventas from ventas where id_sucursal = ? group by date_format(fecha,'%Y-%m') order by fecha limit ?";
		
		global $conn;
		$val = array( $sucursal, $meses );
		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( $foo[0] , $foo[1] ) );
		}
		return $res;

	}

}
