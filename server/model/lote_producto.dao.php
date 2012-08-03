<?php

require_once ('Estructura.php');
require_once("base/lote_producto.dao.base.php");
require_once("base/lote_producto.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** LoteProducto Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link LoteProducto }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class LoteProductoDAO extends LoteProductoDAOBase
{
    
    /*public static function ajustarLoteProducto($id_lote, $id_producto){
        
        
        global $conn;
        
        $query = "";
        
        $rs = $conn->Execute($query, $data);

        $res = array();

        foreach ($rs as $foo) {
            array_push($res, $foo);
        }
        
        return $res;	
        
    }*/
}
