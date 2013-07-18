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

      if(sizeof($extra_params) == 0) return false;
      
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
      if(is_array($extra_params)){
        $extra_params = (object)$extra_params;
      }


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


	public static function getVals($tabla, $id_pk_tabla) {
		global $conn;
		$sql = "select
					epe.campo,
					epe.tipo,
					epe.enum,
					epe.longitud,
					epe.obligatorio,
					epe.caption, 
					epe.descripcion,
					epv.val
				from 
					extra_params_valores epv,
					extra_params_estructura epe
				where
					epv.id_pk_tabla = ?
					and epv.id_extra_params_estructura in (select id_extra_params_estructura from extra_params_estructura where tabla = ?)
					and epv.id_extra_params_estructura = epe.id_extra_params_estructura;";

		$val = array($id_pk_tabla, $tabla);
		$conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$rs = $conn->Execute($sql, $val);
		return $rs->GetRows();
	}





  
}
