<?php


/**
  * (C) 2012 Caffeina Software
  *
  * Description:
  *      Renderers
  *
  * Author:
  *     Alan Gonzalez (alan)
  *
  ***/
class R {

	public static function DescripcionRolFromId($id_rol) {
		return RolDAO::getByPK($id_rol) ? RolDAO::getByPK($id_rol)->getNombre() : self::NonExistent() ;
	}

	private static function NonExistent( ) {
		return "<font style='color:gray'>No existe</font>";
	}

	static function UserFullNameFromId( $user_id ) {
		if ( is_null( $u = UsuarioDAO::getByPK( $user_id ) ) ) {
			return self::NonExistent();
		}else{
			return $u->getNombre( );
		}
	}

	public static function ConceptoGastoFromId($id){
		$v = ConceptoGastoDAO::getByPK($id);
		if(is_null($v)) return R::NonExistent();
		return $v->getNombre();
	}

	public static function ConceptoIngresoFromId($id){
		$v = ConceptoIngresoDAO::getByPK($id);
		if(is_null($v)) return R::NonExistent();
		return $v->getNombre();
	}

	public static function NombreDocumentoBaseFromId($id){
		$v = DocumentoBaseDAO::getByPK($id);
		if(is_null($v)) return R::NonExistent();
		return $v->getNombre();
	}

	public static function NombreDocumentoFromId( $id_documento ){
		$v = DocumentoDAO::getDocumentWithValues( $id_documento );
		for ($i=0; $i < sizeof($v); $i++) { 
			if( strtolower($v[$i]["campo"]) == "titulo"){
				return $v[$i]["val"];
			} else if( strtolower($v[$i]["campo"]) == "nombre"){
				return $v[$i]["val"];
			}
		}
		return R::NonExistent();
	}

	static function UserFirstNameFromId( $user_id ) {

	}

	static function FriendlyDateFromUnixTime( $unixtime ) {
		return FormatTime( $unixtime );
	}

	static function MoneyFromDouble( $foo ) {
		return FormatMoney( $foo );
	}


	static function RazonSocialFromIdEmpresa( $id_empresa ) {
		if ( is_null( $u = EmpresaDAO::getByPK( $id_empresa ) ) ) {
			return self::NonExistent();
		} else {
			return $u->getRazonSocial( );
		}
	}

}//class R