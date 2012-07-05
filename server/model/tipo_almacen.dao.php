<?php

require_once ('Estructura.php');
require_once("base/tipo_almacen.dao.base.php");
require_once("base/tipo_almacen.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** TipoAlmacen Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link TipoAlmacen }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class TipoAlmacenDAO extends TipoAlmacenDAOBase
{

        /**
         * regresa un arreglo que contiene los datos de todas las planeaciones sin importar si estan activas o no, sin importar el id_empleado ni id_cliente
         */
        public static function buscarTipoAlmacen( $data, $start = null, $limit = null, $sort = "id_tipo_almacen", $dir = "DESC", $search = null ){

                global $conn;
                $query = "";

                $query .= "SELECT * ";
                $query .= "FROM tipo_almacen ";
                $query .= "WHERE activo = ? ";
                $query .= $search != null?" and descripcion LIKE '%{$search}%' ":" ";                
				$query .= "ORDER BY {$sort} {$dir} ";
                $query .= $limit != null?" LIMIT {$start}, {$limit} ":"";               			

                $rs = $conn->Execute($query, $data);            

                $res = array();
                
                foreach ($rs as $foo  ) {
                        array_push( $res, $foo );
                }               

                return $res;
        }

}








