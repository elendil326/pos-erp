<?php

require_once ('Estructura.php');
require_once("base/unidad_medida.dao.base.php");
require_once("base/unidad_medida.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** UnidadMedida Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link UnidadMedida }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class UnidadMedidaDAO extends UnidadMedidaDAOBase
{
public static function convertir( $id_unidad_desde,  $id_unidad_destino,  $cantidad_desde )
  {

      //buscar esas unidades
      $uFrom = self::getByPK( $id_unidad_desde );
      $uTo = self::getByPK( $id_unidad_destino );

      if(is_null( $uFrom)) throw new InvalidDataException("La unidad no existe");
      if(is_null( $uTo)) throw new InvalidDataException("La unidad no existe");


      //verifiquemos que las dos unidades sean de la misma categoria
      if($uFrom->getIdCategoriaUnidadMedida() != $uTo->getIdCategoriaUnidadMedida()){
        throw new BusinessLogicExpetion("No se pueden convertir entre diferentes categorias");
      }



      //busquemos que esa categoria tena una referencia
      $uRef = self::search( new UnidadMedida( array( 
            "id_categoria_unidad_medida" => $uTo->getIdCategoriaUnidadMedida(),
            "factor_conversion" => 1
         )));



      if(sizeof($uRef) != 1) throw new BusinessLogicExpetion("No hay unidad de referencia");

      $uRef = $uRef[0];

      //convertir a referencia
      $intermediate = $cantidad_desde * $uRef->getFactorConversion();

      //convertir a destino
      $result = $intermediate / $uTo->getFactorConversion();

      //regresar
      return $result;

  }
}
