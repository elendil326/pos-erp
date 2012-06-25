<?php

require_once ('Estructura.php');
require_once("base/extra_params_estructura.dao.base.php");
require_once("base/extra_params_estructura.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** ExtraParamsEstructura Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ExtraParamsEstructura }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class ExtraParamsEstructuraDAO extends ExtraParamsEstructuraDAOBase
{
  public static function getByTabla($tabla){

      $sql = "SELECT * FROM extra_params_estructuras WHERE ( tabla = ? ) LIMIT 1;";
      $params = array(  $tabla );

      global $conn;
      try{
        $rs = $conn->Execute($sql, $params);  
      }catch(ADODB_Exception $ae){
        Logger::error($ae);
        return array();
      }
      

      $ar = array();
      foreach ($rs as $foo) {
        $bar =  new ExtraParamsEstructura($foo);
        array_push( $ar,$bar);
      }
      return $ar;
      

  }
}
