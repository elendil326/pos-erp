<?php

require_once ('Estructura.php');
require_once("base/empresa.dao.base.php");
require_once("base/empresa.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Empresa Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Empresa }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class EmpresaDAO extends EmpresaDAOBase
{

	public function getByRFC( $rfc ){

		return self::search( new Empresa( array( "rfc" => $rfc ) ) );

	}

}
