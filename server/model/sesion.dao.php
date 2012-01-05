<?php

require_once ('Estructura.php');
require_once("base/sesion.dao.base.php");
require_once("base/sesion.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Sesion Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Sesion }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class SesionDAO extends SesionDAOBase
{
	public static function getCurrentUser( $auth_token = null ){
		
		global $conn;
		$sql = "select u.* from usuario u, sesion s where u.id_usuario = s.id_usuario and s.auth_token = ?";
      
      	$params = array( $auth_token );

      $rs = $conn->GetRow($sql, $params);

      if(count($rs) === 0)
      {
        return NULL;
      }


      return new Usuario($rs);
	}
}
