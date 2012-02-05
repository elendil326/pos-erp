<?php


          
	class InstanciasController {
		
		/**
		 * Crear una nueva instancia
		 * 
		 * 
		 * */
		public static function Nueva(
			$instance_token = null,
			$descripcion = null
		){
			
			if( is_null($instance_token) ){
				return self::Nueva( md5(rand()), $descripcion );
			}
			
			//primero busquemos ese token
			if( !is_null(self::BuscarPorToken( $instance_token ) ) ){
				Logger::warn("Instance `$instance_token` ya existe. Abortando `InstanciasController::Nueva()`.");
				return null;
			}
			
			//buscar que no exista esta instancia
			global $POS_CONFIG;
			
			
			//insertar registro en `instances` para que me asigne un id
			$sql = "INSERT INTO  `instances` ( `instance_id` ,`instance_token` ,`descripcion`  )VALUES ( NULL ,  ?,  ? );";
			
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( $sql, array( $instance_token, $descripcion )  );
				$I_ID = $POS_CONFIG["CORE_CONN"]->Insert_ID();
			
			}catch(Exception $e){
				Logger::error($e);
				return null;
				
			}
			

			
			$DB_NAME = strtolower("POS_INSTANCE_" . $I_ID);
			
			//crear la bd
			$sql = "CREATE DATABASE  $DB_NAME DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;";
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( $sql  );
				
			}catch(Exception $e){
				Logger::error($e);
				return null;
			}
			
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( "CREATE USER ?@'localhost' IDENTIFIED BY  ?;", array( $DB_NAME, $DB_NAME ) );
				
			}catch(Exception $e){
				Logger::error($e);
				return null;
				
			}


			$sql = "GRANT USAGE ON * . * TO  ?@'localhost' IDENTIFIED BY  ? WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;";
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( $sql, array( $DB_NAME, $DB_NAME ) );
				
			}catch(Exception $e){
				Logger::error($e);
				return null;
				
			}

			$HOST = $POS_CONFIG['CORE_DB_HOST'];
			$sql = "GRANT ALL PRIVILEGES ON  `$DB_NAME` . * TO  '$DB_NAME'@'$HOST';";
			
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( $sql );
				
			}catch(Exception $e){
				Logger::error($e);
				return null;
				
			}

			//conectarse a la nueva bd
			$i_conn = null;

			try{

			    $i_conn = ADONewConnection($POS_CONFIG["CORE_DB_DRIVER"]);
			    $i_conn->debug = false;
			    $i_conn->PConnect( $POS_CONFIG["CORE_DB_HOST"] , $DB_NAME, $DB_NAME, $DB_NAME );

			    if(!$i_conn)
			    {

			    	Logger::error( "Imposible conectarme a la base de datos de la instancia recien creada." );
					return null;
			    }

			}catch (ADODB_Exception $ado_e){
				Logger::error( $ado_e );
				return null;

			}catch ( Exception $e ){
				Logger::error( $e );
				return null;
				
			}


			//llenar los datos
			$instalation_script = file_get_contents( POS_PATH_TO_SERVER_ROOT . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR . "private" . DIRECTORY_SEPARATOR . "pos_instance.sql");
			$queries = explode(  ";", $instalation_script);
			try {
				//$POS_CONFIG["CORE_CONN"]->BeginTrans();
				
				for ($i=0; $i < sizeof($queries); $i++) { 
					if(strlen( trim( $queries[$i] ) ) == 0) continue;
					$i_conn->Execute(  $queries[$i] . ";" );
				}

				
				
			}catch(ADODB_Exception $e){
		        Logger::error($e->msg);
				return null;
		    }
			
			//llenar los datos
			$instalation_script = file_get_contents( POS_PATH_TO_SERVER_ROOT . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR . "private" . DIRECTORY_SEPARATOR . "pos_instance_foundation.sql");
			$queries = explode(  ";", $instalation_script);
			try {
				//$POS_CONFIG["CORE_CONN"]->BeginTrans();
				
				for ($i=0; $i < sizeof($queries); $i++) { 
					if(strlen( trim( $queries[$i] ) ) == 0) continue;
					$i_conn->Execute(  $queries[$i] . ";" );
				}

				
				
			}catch(ADODB_Exception $e){
		        Logger::error($e->msg);
				return null;
		    }
	        
			$sql = "UPDATE  `instances` SET  
					`db_user` 		=  ?,
					`db_password` 	=  ?,
					`db_name` 		=  ?,
					`db_driver` 	=  ?,
					`db_host` 		=  ? WHERE  `instances`.`instance_id` = ?;";

			try{

				$POS_CONFIG["CORE_CONN"]->Execute(  $sql, array( $DB_NAME, $DB_NAME, $DB_NAME, $POS_CONFIG["CORE_DB_DRIVER"], $POS_CONFIG["CORE_DB_HOST"], $I_ID ));



			}catch(ADODB_Exception $e){
		        Logger::error($e->msg);
				return null;
		    }					

			return (int)$I_ID;
		}
		
		
		
		public static function BuscarPorId( $I_ID ){
			global $POS_CONFIG;
			
			$sql = "select * from instances where instance_id = ?;";
			try{
				$res = $POS_CONFIG["CORE_CONN"]->GetRow( $sql , array( $I_ID ) );				
				
			}catch (ADODB_Exception $ado_e){
				Logger::error( $ado_e );
				return null;

			}catch ( Exception $e ){
				Logger::error( $e );
				return null;
				
			}

			
			if(empty($res)) return NULL;
			
			return $res;
		}
		
		
		public static function BuscarPorToken($instance_token = null){
			
			global $POS_CONFIG;
			
			$sql = "select * from instances where instance_token = ?";
			
			$res = $POS_CONFIG["CORE_CONN"]->GetRow( $sql , array( $instance_token ) );
			
			if(empty($res)) return NULL;
			
			return $res;
			
			
		}
		
		public static function Buscar(  ){
			
			global $POS_CONFIG;
			
			$sql = "select * from instances";
											//($sql,$inputarr=false,$force_array=false,$first2cols=false)
			$res = $POS_CONFIG["CORE_CONN"]->GetAssoc( $sql, false, false, false );
			
			if(empty($res)) return NULL;
			
			$a = array();
			
			foreach ($res as $v) {
				array_push( $a, $v );
			}
			
			return $a;
			
			
		}
		
		public static function Eliminar($instance_token){
			
		}
	}