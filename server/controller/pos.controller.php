<?php

require_once("controller/clientes.controller.php");
require_once("controller/inventario.controller.php");
require_once("controller/autorizaciones.controller.php");
require_once("model/pos_config.dao.php");

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
			/* * 
			DEPRECADO
			if(HEARTBEAT_METHOD_TRIGGER){
				$foo = PosConfigDAO::getByPK("DB_VER");
				
				if($foo == null){
					Logger::log("HEARTBEAT METHOD ES CON TRIGGER, PERO NO HAY COLUMNA DE DB_VER !!");
					 
				}
				
				$hash = $foo->getValue();
			}else{
							
			}
			* */
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
	
	
	
	
	
	/**
	 * 
	 * 
	 * 
	 * */
	public static function getSucursalConfig()
	{
		
		$config = array(  );
		
		$config["POS_MULTI_SUCURSAL"] 		= POS_MULTI_SUCURSAL;
		$config["POS_COMPRA_A_CLIENTES"] 	= POS_COMPRA_A_CLIENTES;
		$config["POS_MODULO_CONTABILIDAD"] 	= POS_MODULO_CONTABILIDAD;
		
		$config["EXT_AJAX_TIMEOUT"] 		= 10000;
		$config["CHECK_DB_TIMEOUT"] 		= 15000;

		$config["POS_INFO_SUCURSAL"] 		= informacionSucursal();
		$config["POS_DOCUMENTOS"] 			= listarDocumentos();
		$config["POS_LEYENDAS_TICKET"] 		= leerLeyendasTicket();

		return $config;
		
	}
	
	
	
}




if(isset($args['action'])){
	switch($args['action']){
		/** 
		 * 
		 * 
		 * 
		 * */
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

		/** 
		 * 
		 * 
		 * 
		 * */		
		case 1102 : 
			$hash = POS::getPersistencyHash();
			if($hash)
				echo '{ "success" : true, "hash" : "'. $hash .'" }';
			else
				echo '{ "success" : false }';
		break;
		
		
		/** 
		 * 
		 * 
		 * 
		 * */		
		case 1105 : 
			Logger::log(" Conexion reestablecida !");
		break;
		
		
		
		/** 
		 * 
		 * 
		 * 
		 * */
		case 1106 : 
			//Logger::log( json_encode( POS::getSucursalConfig() ) );
			echo ( json_encode( POS::getSucursalConfig() ) );
		break;
		

	}
}
