<?php

require_once ('Estructura.php');
require_once("base/lote_salida_producto.dao.base.php");
require_once("base/lote_salida_producto.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** LoteSalidaProducto Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link LoteSalidaProducto }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class LoteSalidaProductoDAO extends LoteSalidaProductoDAOBase
{
	public static function obtenerSalidaPorProducto($id_producto, $dias = 60){

		$sql = "SELECT 
					le.id_lote_salida,
					lep.id_producto,
					lep.cantidad,
					le.id_lote,
					le.id_usuario,
					le.fecha_registro,
					le.motivo
				FROM 
					lote_salida le,
					lote_salida_producto lep 
				WHERE 
					lep.id_lote_salida = le.id_lote_salida
					and lep.id_producto = ?";


		global $conn;
		$rs = $conn->Execute($sql, array($id_producto));
		$allData = array();
		foreach ($rs as $foo) {
			$foo["tipo"] = "SALIDA";
    		array_push( $allData, $foo);
			//id_billete
		}
		return $allData;

	}
}
