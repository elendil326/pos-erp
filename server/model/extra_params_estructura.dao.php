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


  public static function getByTablaCampo($tabla, $campo){
    $tb = new ExtraParamsEstructura(array("tabla" => $tabla, "campo" => $campo ));
    $tbr = self::search( $tb );

    if( sizeof($tbr) != 1){ return NULL;}

    return $tbr[ 0 ] ;

  }

  public static function getByTabla($tabla){

      $sql = "SELECT * FROM extra_params_estructura WHERE ( tabla = ? );";
      $params = array(  $tabla );

      global $conn;

      try{
        $rs = $conn->Execute($sql, $params);  
      }catch(ADODB_Exception $ae){
        Logger::error("EEE:" . $ae);
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
