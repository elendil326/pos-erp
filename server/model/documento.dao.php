<?php

require_once ('Estructura.php');
require_once("base/documento.dao.base.php");
require_once("base/documento.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Anonymous
  * @package docs
  * 
  */
/** Documento Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Documento }. 
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */
class DocumentoDAO extends DocumentoDAOBase
{
	public static function getDocumentWithValues($id_documento) {

		global $conn;

		$sql = "select 
					pe.campo,
					pe.tipo,
					pe.caption,
					pe.descripcion,
					pe.longitud,
					pe.obligatorio,
					pv.val
				from 
					documento as d,
					documento_base db,
					extra_params_estructura pe,
					extra_params_valores pv
				where
					d.id_documento_base = db.id_documento_base
					and pe.tabla = CONCAT('documento_base-', db.id_documento_base)
					and pv.id_pk_tabla = d.id_documento
					and pv.id_extra_params_estructura = pe.id_extra_params_estructura
					and d.id_documento = ?";

		$params = array( $id_documento );

		$rs = $conn->Execute($sql, $params);

		return $rs->GetArray(); 

	}
}
