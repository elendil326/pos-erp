<?php

require_once ('Estructura.php');
require_once("base/usuario.dao.base.php");
require_once("base/usuario.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Usuario Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Usuario }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class UsuarioDAO extends UsuarioDAOBase
{
  static function listarClientes()
  {
    
    $sql = "SELECT * from usuario where id_clasificacion_cliente != NULL order by nombre";

    global $conn;
    $rs = $conn->Execute($sql);
    
    $allData = array();

    foreach ($rs as $foo)
    {
      $bar = new Usuario($foo);
        array_push( $allData, $bar);
        //self::pushRecord( $bar, $foo["id_usuario"] );
    }

    return $allData;
        
  }
}
