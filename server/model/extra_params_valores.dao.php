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



  public static function setVals($tabla, $extra_params, $fk_id){
      if(is_null($extra_params)){
        return false;
      }
      
      //buscar esa madre
      try{
        $es = new ExtraParamsEstructura(array(
              "tabla" => $tabla
          ));  
      }catch(Exception $e){
        Logger::error($e);
        return false;
      }
      

      
      try{
        $ncols = ExtraParamsEstructuraDAO::search( $es );

      }catch(ADODB_Exception $e){
        Logger::error($e);
        return false;
      }


      if(sizeof($ncols) == 0){
        //no hay nuevas columnas de esta tabla
        return false;
      }

      /*
      $extra_params
      object(stdClass)#30 (3) {
        ["esposa"]=>
        string(1) "0"
        ["date"]=>
        string(19) "2012-07-02T00:00:00"
        ["bool"]=>
        string(1) "1"
      }
      */

      for ($nc=0; $nc < sizeof($ncols); $nc++) { 

          $campo = $ncols[$nc]->getCampo();

          if( property_exists ( $extra_params, $campo ) ){
              //Logger::log("$campo esta definido en el objeto....");  
              //si me enviaron esta propiedad,
              //vamos a insertarla
              $v = ExtraParamsValoresDAO::search(
                                      new ExtraParamsValores(
                                        array(
                                          "id_extra_params_estructura" => $ncols[$nc]->getIdExtraParamsEstructura(),
                                          "id_pk_tabla"=>$fk_id
                                        )
                                      )
                                  );

              if(sizeof($v) == 1){
                //editarlo
                $v = $v[0];
                $v->setVal( $extra_params->$campo );

              }else{
                //crearlo
                $v = new ExtraParamsValores();
                $v->setVal($extra_params->$campo);
                $v->setIdPkTabla($fk_id);
                $v->setIdExtraParamsEstructura($ncols[$nc]->getIdExtraParamsEstructura());

              }


              //salvarlo
              try{
                ExtraParamsValoresDAO::save( $v );

              }catch(Exception $e){
                throw $e;

              }
          }
          
      }


  }






  public static function getVals($tabla, $id_pk_tabla){
    //ver si esta tabla existe

    //buscar esa madre
    try{
      $es = new ExtraParamsEstructura(array(
            "tabla" => $tabla
        ));  
    }catch(Exception $e){
      Logger::error($e);
      return array();
    }
    

    
    try{
      $ncols = ExtraParamsEstructuraDAO::search( $es );

    }catch(ADODB_Exception $e){
      Logger::error($e);
      return array();
    }


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
