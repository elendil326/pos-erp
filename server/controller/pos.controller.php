<?php

require_once("controller/clientes.controller.php");
require_once("controller/inventario.controller.php");
require_once("controller/autorizaciones.controller.php");

class POS{
	
	
	/**
      * Revisar persistencia.
      *
      * Esta funcion recibe un hash md5 generado por
      * {POS::getPersistencyHash} y regresa verdadero si
      * es que existe un cambio desde que se genero ese
      * hash. Regresa falso si no.
      *
      * @param String Una cadena que contiene un hash MD5
      * @return boolean Verdadero si el hash actual del sistema es igual al argumento, falso si no.
      **/
	public static function checkPersistencyHash( $hashToCheck ){
		return self::getPersistencyHash() == $hashToCheck;
	}
	
	
	
	
	
	
	
	/**
      * Obtener el hash de persistencia.
      *
      * Esta funcion genera un hash de persistencia
      * basado en el estado actual de la base de datos.
      * 
      * @return String Una cadena que contiene un hash de md5
	  * @see 
      *
      **/
	public static function getPersistencyHash(){
		try{
			$hash = md5( json_encode( listarClientesDeudores() )
			 		. json_encode( listarClientes() )
			 		. json_encode( listarInventario( $_SESSION["sucursal"] ) )
			 		. json_encode( autorizacionesSucursal( $_SESSION["sucursal"] ) ) );
		}catch(Exception $e){
			Logger::log( $e );
			$hash = null;
		}
		
		return $hash;

	}
	
	
	
}




if(isset($args['action'])){
	switch($args['action']){
		case 1101 : 
		
			if(!isset($args["hash"])){
				echo '{ "success" : false }';
				return;
			}
			
			if( !POS::checkPersistencyHash($args["hash"]) ){

				//si el hash no es el mismo, regresar el nuevo hash
				$hash = POS::getPersistencyHash();
				if($hash)
					echo '{ "success" : true, "hash" : "'. $hash .'" }';
				else
					echo '{ "success" : false }';
			}else{
				//de lo contrario no regresar nada para ahorrar
				//bandwitdth
			}
		break;
		
		case 1102 : 
			$hash = POS::getPersistencyHash();
			if($hash)
				echo '{ "success" : true, "hash" : "'. $hash .'" }';
			else
				echo '{ "success" : false }';
		break;
	}
}