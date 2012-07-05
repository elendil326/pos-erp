<?php

require_once ('Estructura.php');
require_once("base/lote_entrada_producto.dao.base.php");
require_once("base/lote_entrada_producto.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** LoteEntradaProducto Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link LoteEntradaProducto }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class LoteEntradaProductoDAO extends LoteEntradaProductoDAOBase
{
	public static function obtenerEntradaPorProducto($id_producto, $dias = 60){

		$sql = "SELECT 
					le.id_lote_entrada,
					lep.id_producto,
					lep.cantidad,
					le.id_lote,
					lep.id_unidad,
					le.id_usuario,
					le.fecha_registro,
					le.motivo
				FROM 
					lote_entrada le,
					lote_entrada_producto lep 
				WHERE 
					lep.id_lote_entrada = le.id_lote_entrada
					and lep.id_producto = ?";


		global $conn;
		$rs = $conn->Execute($sql, array($id_producto));
		$allData = array();
		foreach ($rs as $foo) {
			$foo["tipo"] = "ENTRADA";
    		array_push( $allData, $foo);
			//id_billete
		}
		return $allData;

	}
}
