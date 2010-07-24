<?php

require_once("base/productos_proveedor.dao.base.php");
require_once("base/productos_proveedor.vo.base.php");
require_once ('Estructura.php');
/** ProductosProveedor Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ProductosProveedor }. 
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */
class ProductosProveedorDAO extends ProductosProveedorDAOBase
{

 /**
 * product_price
 *
 * Funcion que regresa el precio de un producto que este en una sucursal
 * especifica
 *
 * @param <type> $id_producto
 * @param <type> $id_proveedor
 * @param <type> $sucursal
 */
static function product_price($id_producto, $id_proveedor , $sucursal ) {
	
	$query="SELECT precio FROM detalle_inventario NATURAL JOIN inventario i left join
				productos_proveedor pp on (i.id_producto=pp.id_inventario) where i.id_producto=? 
				and id_sucursal=? and id_proveedor=?;"
	$params=array($id_producto , $sucursal, $id_proveedor);
	
	global $conn;
    
	try{
		$rs = $conn->Execute($query,$params);
	}catch(Exception $e){
		$logger->log($e->getMessage(), PEAR_LOG_ERR);
	}
                
	$arr = $rs->GetRows();
	
	return $arr[0][0];
}//fin list_purchasess

}
