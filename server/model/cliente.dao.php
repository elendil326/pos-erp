<?php

require_once ('Estructura.php');
require_once("base/cliente.dao.base.php");
require_once("base/cliente.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Alan Gonzalez
  * @package docs
  * 
  */
/** Cliente Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Cliente }. 
  * @author Alan Gonzalez
  * @access public
  * @package docs
  * 
  */
class ClienteDAO extends ClienteDAOBase
{
	/**
	 *	 Regrsa un arreglo de cuantos clientes se registraron en cada dia para una sucursal dada
	 * 
	 */
	public static function contarClientesRegistradosPorDia( $sucursal ){
		
		
		$sql = "select date_format(fecha_ingreso,'%Y-%m-%d') as fecha, count(*) as clientes_registrados from cliente where id_sucursal = ? group by date_format(fecha_ingreso,'%Y-%m-%d') order by fecha_ingreso ";
		$val = array($sucursal);

		global $conn;


		$rs = $conn->Execute($sql, $val);

		$res = array();
		foreach ($rs as $foo) {
			array_push( $res, array( 
				"fecha" => $foo[0],
				"clientes" => $foo[1] ) );
		}
		return $res;

	}
	
}
