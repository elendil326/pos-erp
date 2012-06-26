<?php

require_once ('Estructura.php');
require_once("base/extra_params_valores.dao.base.php");
require_once("base/extra_params_valores.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** ExtraParamsValores Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link ExtraParamsValores }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class ExtraParamsValoresDAO extends ExtraParamsValoresDAOBase
{





  public static function getVals($tabla, $id_pk_tabla){
    //ver si esta tabla existe

    //buscar esa madre
    $es = new ExtraParamsEstructura(array(
          "tabla" => $tabla
      ));

    $ncols = ExtraParamsEstructuraDAO::search( $es );

    if(sizeof($ncols) == 0){
      //no hay nuevas columnas de esta tabla
      return array();
    }

    $nvals = new ExtraParamsValores(array( 
        "tabla" => $tabla
      ));


    $out = array();

    for ($nc=0; $nc < sizeof($ncols); $nc++) { 

      $out[$nc] = $ncols[$nc]->asArray();
      $out[$nc]["val"] = null;

      $nvals->setIdExtraParamsEstructura($out[$nc]["id_extra_params_estructura"]);

      $actualvals = ExtraParamsValoresDAO::search( $nvals );

      if(sizeof($actualvals) == 1){
        $out[$nc]["val"] = $actualvals[0]->getVal();
      }

      unset($out[$nc]["id_extra_params_estructura"]);
      unset($out[$nc]["tabla"]);
    }

    

    return $out;

  }





  
}
