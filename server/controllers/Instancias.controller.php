<?php


          
	class InstanciasController {
		
		/**
		 * Crear una nueva instancia
		 * 
		 * 
		 * */
		public static function Nueva( $instance_token = null, $descripcion = null ){
			
			Logger::log("======== NUEVA INSTANCIA =============");
			
			if( is_null($instance_token) ){
				return self::Nueva( md5( time() ) , $descripcion );
			}
			
			//primero busquemos ese token
			if( !is_null(self::BuscarPorToken( $instance_token ) ) ){
				Logger::error("Instance `$instance_token` ya existe. Abortando `InstanciasController::Nueva()`.");
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
			
			Logger::log("======== / NUEVA INSTANCIA =============");
			
			
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
		
		
		public static function BuscarRequests(  ){
			
			global $POS_CONFIG;
			
			$sql = "select * from instance_request order by fecha desc";
			
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

		public static function Actualizar_Todas_Instancias($instance_ids){
			$ids_string =" WHERE instance_id = ";
			$ids =json_decode($instance_ids);
			
			for($i=0; $i< count($ids); $i++){
				if($i == 0)
					$ids_string .= "".$ids[$i];
				else
					$ids_string .= " OR instance_id = ".$ids[$i];
			}
			
			Logger::log("Updating Instances}");
			$result = "";
			$out = "";
			$file_name_cons = 'db-backup-'.time().'.sql';
			$destiny_file = '../../../static_content/db_backups/';
			global $POS_CONFIG;
			$sql = "SELECT * FROM instances $ids_string;";
			
			
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
			//conexion a la instancia
			$sql = "SELECT * FROM instances WHERE ( instance_id = {$instance_id} ) LIMIT 1;";
			
			Logger::log("------ Finding db connection, query: {$sql}");
			global $POS_CONFIG;
			$rs = $POS_CONFIG["CORE_CONN"]->GetRow($sql);
			

			if(count($rs) === 0)
			{
				Logger::warn("La instancia con el id {". $instance_id ."} no exite !");
				die(header("HTTP/1.1 404 NOT FOUND"));
			}
			$instance_con["INSTANCE_CONN"] = null;
			
			try{

				$instance_con["INSTANCE_CONN"] = ADONewConnection($rs["db_driver"]);
				$instance_con["INSTANCE_CONN"]->debug = $rs["db_debug"];
				$instance_con["INSTANCE_CONN"]->PConnect($host, $user, $pass, $name);

				if(!$instance_con["INSTANCE_CONN"])
				{

					Logger::error( "Imposible conectarme a la base de datos de la instancia {$instance_id}." );
					die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));    
				}

			} catch (ADODB_Exception $ado_e) {
	
				Logger::error( $ado_e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
		
		

			}catch ( Exception $e ){
				Logger::error( $e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}

			if($instance_con["INSTANCE_CONN"] === NULL){
				Logger::error("Imposible conectarse con la base de datos de la instancia {$instance_id}");
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}
	
			$instance_db_con = $instance_con["INSTANCE_CONN"];
			//fin con
			
			$instance_db_con ->Execute ("SET foreign_key_checks = 0");//deshabilitar llave foraneas

			$tables = array();
			$result = $instance_db_con->Execute('SHOW TABLES');
			
			//se eliminan las tablas
			while($row = $result->FetchRow()){//row = nombre de la tabla
				$rss = $instance_db_con->Execute("DROP TABLE IF EXISTS {$name}.".$row[0]." CASCADE;");											
			}
			return null;
		}

		public static function Insertar_Estructura_Tablas_A_BD($instance_id,$host,$user, $pass, $name){
			$out ="";
			//conexion a la instancia
			$sql = "SELECT * FROM instances WHERE ( instance_id = {$instance_id} ) LIMIT 1;";
			
			global $POS_CONFIG;
			$rs = $POS_CONFIG["CORE_CONN"]->GetRow($sql);

			if(count($rs) === 0)
			{
				Logger::warn("La instancia con el id {". $instance_id ."} no exite !");
				die(header("HTTP/1.1 404 NOT FOUND"));
			}

			$instance_con["INSTANCE_CONN"] = null;
			
			try{

				$instance_con["INSTANCE_CONN"] = ADONewConnection($rs["db_driver"]);
				$instance_con["INSTANCE_CONN"]->debug = $rs["db_debug"];
				$instance_con["INSTANCE_CONN"]->PConnect($host, $user, $pass, $name);

				if(!$instance_con["INSTANCE_CONN"])
				{

					Logger::error( "Imposible conectarme a la base de datos de la instancia {$instance_id}." );
					die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));    
				}

			} catch (ADODB_Exception $ado_e) {
	
				Logger::error( $ado_e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
		
		

			}catch ( Exception $e ){
				Logger::error( $e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}

			if($instance_con["INSTANCE_CONN"] === NULL){
				Logger::error("Imposible conectarse con la base de datos de la instancia {$instance_id}");
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}
	
			$instance_db_con = $instance_con["INSTANCE_CONN"];
			//fin con

			Logger::log("Inserting Tables to instance {$instance_id}");
			//insertar tablas
			$instalation_script = file_get_contents( POS_PATH_TO_SERVER_ROOT . DIRECTORY_SEPARATOR .  ".." . DIRECTORY_SEPARATOR . "private" . DIRECTORY_SEPARATOR . "pos_instance.sql");
			$queries = explode(  ";", $instalation_script);
			try {
				
				for ($i=0; $i < sizeof($queries); $i++) { 
					if(strlen( trim( $queries[$i] ) ) == 0) continue;
					$rs = $instance_db_con ->Execute (  $queries[$i] . ";" );
					if($rs)
						;
					else{
						Logger::log("Error al ejecuar la consulta: {$queries[i]}");
						$out.= "Error al ejecutar la consulta: {$queries[i]}"."\n";
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
			//conexion a la instancia
			$sql = "SELECT * FROM instances WHERE ( instance_id = {$instance_id} ) LIMIT 1;";
			
			global $POS_CONFIG;	
			$rs = $POS_CONFIG["CORE_CONN"]->GetRow($sql);

			if(count($rs) === 0)
			{
				Logger::warn("La instancia con el id {". $instance_id ."} no exite !");
				die(header("HTTP/1.1 404 NOT FOUND"));
			}
			
			$instance_con["INSTANCE_CONN"] = null;
			
			try{

				$instance_con["INSTANCE_CONN"] = ADONewConnection($rs["db_driver"]);
				$instance_con["INSTANCE_CONN"]->debug = $rs["db_debug"];
				$instance_con["INSTANCE_CONN"]->PConnect($host, $user, $pass, $name);

				if(!$instance_con["INSTANCE_CONN"])
				{

					Logger::error( "Imposible conectarme a la base de datos de la instancia {$instance_id}." );
					die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));    
				}

			} catch (ADODB_Exception $ado_e) {
	
				Logger::error( $ado_e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
		
		

			}catch ( Exception $e ){
				Logger::error( $e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}

			if($instance_con["INSTANCE_CONN"] === NULL){
				Logger::error("Imposible conectarse con la base de datos de la instancia {$instance_id}");
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}
	
			$instance_db_con = $instance_con["INSTANCE_CONN"];
			//fin con

			
			//llenar los datos respaldados
			$data_script = file_get_contents( $source_file );
			$queries = explode(  ";\n", $data_script);
			try {
				
				for ($i=0; $i < sizeof($queries); $i++) { 
					if(strlen( trim( $queries[$i] ) ) == 0) continue;
					$rs = $instance_db_con->Execute(  $queries[$i] . ";" );
					if($rs)
						;
					else{
						Logger::log("Consulta: {$queries[$i]} ; Error! ");
						$out.= "Consulta: {$queries[$i]} ; Error! "."\n";
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
			
			//conexion a la instancia
			$sql = "SELECT * FROM instances WHERE ( instance_id = {$instance_id} ) LIMIT 1;";
			
			global $POS_CONFIG;
			$rs = $POS_CONFIG["CORE_CONN"]->GetRow($sql);

			if(count($rs) === 0)
			{
				Logger::warn("La instancia con el id {". $instance_id ."} no exite !");
				die(header("HTTP/1.1 404 NOT FOUND"));
			}
			$instance_con["INSTANCE_CONN"] = null;
			
			try{

				$instance_con["INSTANCE_CONN"] = ADONewConnection($rs["db_driver"]);
				$instance_con["INSTANCE_CONN"]->debug = $rs["db_debug"];
				$instance_con["INSTANCE_CONN"]->PConnect($host, $user, $pass, $name);

				if(!$instance_con["INSTANCE_CONN"])
				{

					Logger::error( "Imposible conectarme a la base de datos de la instancia {$instance_id}." );
					die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));    
				}

			} catch (ADODB_Exception $ado_e) {
	
				Logger::error( $ado_e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
		
		

			}catch ( Exception $e ){
				Logger::error( $e );
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}

			if($instance_con["INSTANCE_CONN"] === NULL){
				Logger::error("Imposible conectarse con la base de datos de la instancia {$instance_id}");
				die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
			}
	
			$instance_db_con = $instance_con["INSTANCE_CONN"];
			//fin con

			//get all of the tables
		  	if($tables == '*'){
				$tables = array();
				//$result = mysql_query('SHOW TABLES');
				$sql = "SHOW TABLES";
				$ress =  $instance_db_con->Execute($sql);
				while($row = $ress-> FetchRow() )
			  		$tables[] = $row[0];
		  	}else{
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			}

			$return = "";

		  	//cycle through
		  	foreach($tables as $table)
		  	{
				$sql2 = 'SELECT * FROM '.$table;				
				$result = $instance_db_con->Execute($sql2);				
				$num_fields = $result->FieldCount();

				$sql3 = 'SHOW COLUMNS FROM '.$table;				
				$cols = $instance_db_con->Execute($sql3);
				$cols_str = " (";
				while( $rw = $cols->FetchRow() )
					$cols_str .= "`".$rw[0]."`, ";
				
				$cols2 = $instance_db_con->GetArray($sql3);			

				$cols_str = substr ( $cols_str , 0 , -2 );
				$cols_str .= " ) ";
				$cmd = "";
				if($backup_values)
				{
					for ($i = 0; $i < $num_fields; $i++) 
					{
					  while($row = $result->FetchRow() )
					  {
						$cmd .= 'INSERT INTO '.$table.$cols_str.' VALUES(';
						for($j=0; $j<$num_fields; $j++) 
						{
							$row[$j] = addslashes($row[$j]);
							$row[$j] = @ereg_replace("\n","\\n",$row[$j]);
							$value = $row[$j];
							
							if ( strlen( trim($value) ) > 0 ) 
							{ 							
								$cmd.= '"'.$row[$j].'"' ; 
							} 
							else 
							{ 
								$null_column = $cols2[$j]['Field'];
								if( $j == ($num_fields -1) )//es el ultimo campo
									$cmd = str_replace(", `".$null_column."` ", '' , $cmd );//no se concatena ya que se hace la susuticion y se devuelve la cadena entera
								else
									$cmd = str_replace("`".$null_column."`, ", '' , $cmd );//no se concatena ya que se hace la susuticion y se devuelve la cadena entera

								Logger::log("COLUMNA SIN VALOR, Se va a reeplazar {$null_column} por '' para que no salga en el Insert");
							}

							if ($j<($num_fields-1)) 
							{ 
								if( strlen( trim($value) ) > 0 )
									$cmd.= ','; 
							}
						}
						$cmd.= ");\n";
						$cmd = str_replace(',)', ')' , $cmd );//no se concatena ya que se hace la susuticion y se devuelve la cadena entera
						$return .= $cmd;
						$cmd ="";
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

		public static function requestDemo($userEmail){
			
			global $POS_CONFIG;
						
			Logger::log("Somebody requested trial instance");
			
			//busquemos si ese email es valido
			
			
			
			//busquemos ese email en la bd
			$sql = "select id_request from instance_request where email = ? ";
			$res = $POS_CONFIG["CORE_CONN"]->GetRow( $sql , array( $userEmail ) );
			
			if(!empty($res)){
				//ya solicito la instancia
				Logger::warn("Este usuario ya ha solicitado instancia antes");
				return false;
			}
			


			//generemos el token a enviar
			$time = time();
			
			$token = md5( $time . "caffeinaSoftware" ) ;
			
			Logger::log("token will be " . $token);

			//insertemos nuevo request
			$sql = "INSERT INTO `instance_request` (`id_request`, `email`, `fecha`, `ip`, `token`)
			 			VALUES ( NULL, ?, ?, ?, ?);";
			
			$res = $POS_CONFIG["CORE_CONN"]->Execute( $sql , array( $userEmail, $time, $_SERVER["REMOTE_ADDR"], $token ) );
			
			$cuerpo = "Bienvenido a su cuenta de POS ERP\n\n";
			$cuerpo .= "Por favor siga el siguiente enlace para continuar con su inscripcion:";
			$cuerpo .= "\n\nhttp://pos2.labs2.caffeina.mx/?t=by_email&key=" . $token;
			
			//enviar el correo electronico
			POSController::EnviarMail(
				$cuerpo, 
				$userEmail, 
				"Bienvenido a POS ERP");
			
		}
		
		
		
		public static function validateDemo($token){
			global $POS_CONFIG;
						
			Logger::log("Somebody requested validate: token: " . $token);

			
			//busquemos ese token en la bd
			$sql = "select id_request, date_validated, email from instance_request where token = ? ";
			$res = $POS_CONFIG["CORE_CONN"]->GetRow( $sql , array( $token ) );
			
			if(empty($res)){
				//ya solicito la instancia
				Logger::error("Este token no existe !");
				return array("success" => false, "reason" => "No existe esta llave de solicitud.");
			}
			
			
			Logger::log("encontre el token para id=" . $res["id_request"]);
			
			
			if(!is_null($res["date_validated"])){
				Logger::log("este ya fue validado");

				
				return array("success" => false, "reason" => "Esta llave de solicitud ya ha sido creada. Para acceder a ella haga click <a href=\"http://pos2.labs2.caffeina.mx/front_ends/". $token ."/\">aqui</a>.");
			}
			
			
			$startTime = time();

					
			
			$iid = self::Nueva( $token, $res["email"] . " requested this instance as a demo" );

			Logger::log("Sending email....");
			
			$cuerpo = "Su nueva instancia de POS ERP ha sido creada con exito !\n\n";
			$cuerpo .= "Puede acceder a su cuenta en la siguiente direccion:";
			$cuerpo .= "\n\nhttp://pos2.labs2.caffeina.mx/front_ends/". $token ."/";
			$cuerpo .= "\n\nHemos creado una cuenta de aministrador para usted, el usuario es: `1` y su contraseña es `123` sin las comillas.";

			
			
			//enviar el correo electronico
			POSController::EnviarMail(
				$cuerpo, 
				$res["email"], 
				"Su cuenta POS ERP esta lista");
			
			
			$sql = "UPDATE  `instance_request`  SET  `date_validated` =  ?, `date_installed` = ?  WHERE `id_request` = ?;";						
			$POS_CONFIG["CORE_CONN"]->Execute( $sql , array( $startTime, time(), $res["id_request"] ) );
			
			Logger::log("Done with installation.");
			
						
			return array("success" => true, "reason" => "Su instancia se ha creado con exito.");
			
			
		}//validateDemo
			
			
	}
