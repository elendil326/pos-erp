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

    /**
      * buscar usuario por usuario y contrasena
      *
      **/
    public static function findUser($user, $password)
    {

      global $conn;

      $sql = "SELECT * FROM usuario WHERE ( id_usuario = ? AND password = ?) LIMIT 1;";

      $params = array( $user, md5($password) );

      $rs = $conn->GetRow($sql, $params);

      if(count($rs) === 0)
      {
        return NULL;
      }

      
      return new Usuario($rs);
    }
}
