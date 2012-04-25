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

      $sql = "SELECT * FROM usuario WHERE ( (id_usuario = ? OR correo_electronico = ? OR codigo_usuario = ? ) AND password = ?) LIMIT 1;";

      $params = array( $user, $user, $user, md5($password) );

      $rs = $conn->GetRow($sql, $params);

      if(count($rs) === 0)
      {
        return NULL;
      }

      
      return new Usuario($rs);
    }



	public static function buscarClientes( $query, $how_many = 5000 ){

		$parts = explode(" ", $query);

		$sql = "select * from usuario where (";
		$val = array();
		$first = true;
		foreach ($parts as $p) {
			if($first){
				$first = false;
				
			}else{
				$sql .= " and ";
			}
			$sql .= "  nombre like ? ";
			array_push($val , "%" . $p . "%");
		}
		
		$sql .= " and id_rol = 5 ) ";

		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array( );
		foreach ($rs as $foo) {
			$bar =  new Usuario($foo);
    		array_push( $ar,$bar);
		}
		
		return $ar;
			
	}
}
