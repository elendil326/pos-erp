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
  * @param mostrar_inactivos 
  * @param id_sucursal
  * @param id_empresa
  * @param orden
  **/
  public static function listarClientes( $id_empresa = null, $id_sucursal = null, $mostrar_inactivos = null, $orden = null )
  {
    $sql = "";
  }


}
