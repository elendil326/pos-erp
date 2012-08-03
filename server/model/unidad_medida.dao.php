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
    
      Logger::log("<<<<<<<<<< se recibieron  ( {$id_unidad_desde}, {$id_unidad_destino}, {$cantidad_desde} ) >>>>>>>>>>>>>");
    
      //buscar esas unidades
      $x = self::getByPK( $id_unidad_desde );
      $y = self::getByPK( $id_unidad_destino );
      $c = $cantidad_desde;

      if(is_null( $x)) throw new InvalidDataException("La unidad no existe (desde)");
      if(is_null( $y)) throw new InvalidDataException("La unidad no existe (destino)");


      //verifiquemos que las dos unidades sean de la misma categoria
      if($x->getIdCategoriaUnidadMedida() != $y->getIdCategoriaUnidadMedida()){
          Logger::error("En UnidadMedidaDAO::convertir(id_unidad_desde $id_unidad_desde, id_unidad_destino $id_unidad_destino, cantidad_desde $cantidad_desde)");
          Logger::error("No se pueden convertir entre diferentes categorias");
          throw new BusinessLogicException("No se pueden convertir entre diferentes categorias");
      }


      /*
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
      $result = $intermediate / $uTo->getFactorConversion();*/
      
      /**
       * ALGORITMO
       * 
       * Se recibe en la funcion :
       * convertir (x, y, c)
       * 
       * Formula : Factor(x) * c / Factor(y)
       * 
       */
      
      

      //regresar
      $r = ($x->getFactorConversion() * $c / $y->getFactorConversion());
      Logger::log("<<<<<<<<<< se transformaron {$cantidad_desde} " . UnidadMedidaDAO::getByPK($id_unidad_desde)->getAbreviacion() .  " a {$r}  " . UnidadMedidaDAO::getByPK($id_unidad_destino)->getAbreviacion() .  " >>>>>>>>>>>>>");
      return $r;

  }
}
