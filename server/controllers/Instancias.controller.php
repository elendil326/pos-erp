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
				return self::Nueva( md5( time() ) , $descripcion );
			}
			
			//primero busquemos ese token
			if( !is_null(self::BuscarPorToken( $instance_token ) ) ){
				Logger::warn("Instance `$instance_token` ya existe. Abortando `InstanciasController::Nueva()`.");
				return null;
			}
			
			//buscar que no exista esta instancia
			global $POS_CONFIG;
			
			
			//insertar registro en `instances` para que me asigne un id
			$sql = "INSERT INTO  `instances` ( `instance_id` ,`fecha_creacion`,`instance_token` ,`descripcion`  )VALUES ( NULL , ".time().",  ?,  ? );";
			
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
				throw $e;

			}
			
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( "CREATE USER ?@'localhost' IDENTIFIED BY  ?;", array( $DB_NAME, $DB_NAME ) );
				
			}catch(Exception $e){
				Logger::error($e);
				throw $e;

				
			}


			$sql = "GRANT USAGE ON * . * TO  ?@'localhost' IDENTIFIED BY  ? WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;";
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( $sql, array( $DB_NAME, $DB_NAME ) );
				
			}catch(Exception $e){
				Logger::error($e);
				throw $e;
				
			}

			$HOST = $POS_CONFIG['CORE_DB_HOST'];
			$sql = "GRANT ALL PRIVILEGES ON  `$DB_NAME` . * TO  '$DB_NAME'@'$HOST';";
			
			try{
				$POS_CONFIG["CORE_CONN"]->Execute( $sql );
				
			}catch(Exception $e){
				Logger::error($e);
				throw $e;
				
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

			Logger::log("Instancia $I_ID creada correctamente... ");
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
			
			$sql = "select * from instances order by fecha_creacion desc";
			
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

		public static function Actualizar_Todas_Instancias(){
			Logger::log("Updating Instances}");
			$result = "";
			$out = "";
			$file_name_cons = 'db-backup-'.time().'.sql';
			$destiny_file = '../../../static_content/db_backups/';
			global $POS_CONFIG;
			$sql = "SELECT * FROM instances;";
			$rs =  $POS_CONFIG["CORE_CONN"]->Execute($sql);
			$instancias = $rs->GetArray();
			
			foreach($instancias as $ins){
				$file_name = 'instance_'.$ins['instance_id'].'-'.$file_name_cons;
				$out = self::backup_only_data($ins['instance_id'],$ins['db_host'], $ins['db_user'], $ins['db_password'], $ins['db_name'], $tables = '*', $backup_values = true, $return_as_string = false,$destiny_file,$file_name);
				if(!is_null($out)){	
					$result.= $out."\n";
					continue;//ya no seguir con el proceso
				}
				
				$out = self::Eliminar_Tablas_BD($ins['instance_id'],$ins['db_host'], $ins['db_user'], $ins['db_password'], $ins['db_name']);				
				if(!is_null($out))
					$result.= $out."\n";
				$out = self::Insertar_Estructura_Tablas_A_BD($ins['instance_id'],$ins['db_host'], $ins['db_user'], $ins['db_password'], $ins['db_name']);
				if(!is_null($out))
					$result.= $out."\n";
				$out = self::Insertar_Datos_Desde_Respaldo($ins['instance_id'],$ins['db_host'], $ins['db_user'], $ins['db_password'], $ins['db_name'],$destiny_file.$file_name);
				if(!is_null($out))
					$result.= $out."\n";
			}
			
			if(strlen($result)>0)
				return $result;
			else 
				return null;
		}

		public static function Eliminar_Tablas_BD($instance_id,$host,$user, $pass, $name){
			Logger::log("Deleting Tables from instance {$instance_id}");
			try{
				$link = @mysql_connect($host,$user,$pass);
				@mysql_select_db($name,$link);
				if($link == null){
					Logger::log( "No se pudo abrir la conexion para la BD: {$name} " );
					return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id}";	
				}
			}catch(ADODB_Exception $e){								
				Logger::log( "No se pudo abrir la conexion para la BD: {$name} ".$e->msg );
				return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id} . Error: ".$e->msg;
			}
			
			mysql_query("SET foreign_key_checks = 0");//deshabilitar llave foraneas

			$tables = array();
			$result = mysql_query('SHOW TABLES');
			
			//se eliminan las tablas
			while($row = mysql_fetch_row($result)){//row = nombre de la tabla
				$rss = mysql_query("DROP TABLE IF EXISTS {$name}.".$row[0]." CASCADE;");											
			}
			return null;
		}

		public static function Insertar_Estructura_Tablas_A_BD($instance_id,$host,$user, $pass, $name){
			$out ="";
			try{
				$link = @mysql_connect($host,$user,$pass);
				@mysql_select_db($name,$link);
				if($link == null){
					Logger::log( "No se pudo abrir la conexion para la BD: {$name} " );
					return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id}";	
				}
			}catch(ADODB_Exception $e){								
				Logger::log( "No se pudo abrir la conexion para la BD: {$name} ".$e->msg );
				return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id} . Error: ".$e->msg;
			}

			Logger::log("Inserting Tables to instance {$instance_id}");
			//insertar tablas
			$instalation_script = file_get_contents( POS_PATH_TO_SERVER_ROOT . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR . "private" . DIRECTORY_SEPARATOR . "pos_instance.sql");
			$queries = explode(  ";", $instalation_script);
			try {
				
				for ($i=0; $i < sizeof($queries); $i++) { 
					if(strlen( trim( $queries[$i] ) ) == 0) continue;
					$rs = mysql_query(  $queries[$i] . ";" );
					if($rs)
						;
					else{
						Logger::log(mysql_error());
						$out.= mysql_error()."\n";
					}
				}			
				
			}catch(ADODB_Exception $e){
		        Logger::error($e->msg);
				return $e->msg;
		    }
			
			if(strlen($out) > 0)
				return $out;
			
			return null;
		}

		public static function Insertar_Datos_Desde_Respaldo($instance_id, $host, $user, $pass, $name,$source_file){
			Logger::log( "Restoring data from file to Instance DB {$instance_id}");
			
			$out ="";
			try{
				$link = @mysql_connect($host,$user,$pass);
				@mysql_select_db($name,$link);
				if($link == null){
					Logger::log( "No se pudo abrir la conexion para la BD: {$name} " );
					return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id}";	
				}
			}catch(ADODB_Exception $e){								
				Logger::log( "No se pudo abrir la conexion para la BD: {$name} ".$e->msg );
				return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id} . Error: ".$e->msg;
			}

			
			//llenar los datos respaldados
			$data_script = file_get_contents( $source_file );
			$queries = explode(  ";", $data_script);
			try {
				
				for ($i=0; $i < sizeof($queries); $i++) { 
					if(strlen( trim( $queries[$i] ) ) == 0) continue;
					$rs = mysql_query(  $queries[$i] . ";" );
					if($rs)
						;
					else{
						Logger::log("Consulta: {$queries[$i]} ; Error: ".mysql_error());
						$out.= mysql_error()."\n";
					}
				}				
				
			}catch(ADODB_Exception $e){
		        Logger::error($e->msg);
				return $e->msg;
		    }
			
			if(strlen($out) > 0)
				return $out;
			
			return null;

		}

		
		public static function backup_only_data($instance_id, $host, $user, $pass, $name, $tables = '*', $backup_values = true, $return_as_string = false,$destiny_file, $file_name){
			Logger::log( "Backup to Instance {$instance_id}");
			try{
				$link = @mysql_connect($host,$user,$pass);
				@mysql_select_db($name,$link);
				if($link == null){
					Logger::log( "No se pudo abrir la conexion para la BD: {$name} " );
					return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id}";	
				}
			}catch(ADODB_Exception $e){								
				Logger::log( "No se pudo abrir la conexion para la BD: {$name} ".$e->msg );
				return "No se pudo abrir la conexion para la BD: {$name} con id: {$instance_id} . Error: ".$e->msg;
			}

			//get all of the tables
		  	if($tables == '*'){
				$tables = array();
				$result = mysql_query('SHOW TABLES');

				while($row = mysql_fetch_row($result))
			  		$tables[] = $row[0];
		  	}else{
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			}

			$return = "";

		  	//cycle through
		  	foreach($tables as $table)
		  	{
				$result = mysql_query('SELECT * FROM '.$table);
				$num_fields = mysql_num_fields($result);

				//$return.= 'DROP TABLE '.$table.';';
				$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
				//$return.= "\n\n".$row2[1].";\n\n";

				if($backup_values)
				{
					for ($i = 0; $i < $num_fields; $i++) 
					{
					  while($row = mysql_fetch_row($result))
					  {
						$return.= 'INSERT INTO '.$table.' VALUES(';
						for($j=0; $j<$num_fields; $j++) 
						{
						  $row[$j] = addslashes($row[$j]);
						  $row[$j] = @ereg_replace("\n","\\n",$row[$j]);
						  if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
						  if ($j<($num_fields-1)) { $return.= ','; }
						}
						$return.= ");\n";
					  }
					}			
				}

				$return.="\n";
		  	}
	
			if($return_as_string)
				return $return;

			$fname = $destiny_file.$file_name;
			try{
			  	$handle = @fopen($fname,'w+');
			  	@fwrite($handle, $return);
			  	@fclose($handle);
			}catch(Exception $e){
				Logger::log( $e->getMessage() );
				return $e->getMessage();				
			}

			return null;//cuando regresa null todo bien

		}//fin back_up tables

		public static function formatfilesize( $data ) {
	        // bytes
	        if( $data < 1024 ) {
	            return $data . " bytes";
	        }

	        // kilobytes
	        else if( $data < 1024000 ) {
	            return round( ( $data / 1024 ), 1 ) . "k";
	        }

	        // megabytes
	        else {
	            return round( ( $data / 1024000 ), 1 ) . " MB";
	        }
		}

	}
