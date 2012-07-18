<?php



	class JediLogin{
		
		
		
		public static function login($user_nickname, $password){
			
			Logger::log("JEDI: Probando login para ". $user_nickname ."... ");
			
			global $core_conn;
			
			$sql = "SELECT * FROM users WHERE (nickname = ? AND pass = ? ) LIMIT 1;";
			
			$params = array(  $user_nickname, $password );

			$rs = $core_conn->GetRow($sql, $params);
			
			if(count($rs)==0){
				Logger::log("JEDI: Credenciales invalidas...");				
				return false;	
			}
			
			Logger::log("Login correcto !");
			
			$_SESSION['ip']		= getip();
		    $_SESSION['pass'] 	= $password;
			$_fua 				= $_SERVER['HTTP_USER_AGENT'];
		    $_SESSION['ua'] 	= $_fua;
			$_SESSION['grupo']  = "JEDI";
			$_SESSION['userid'] = $rs["jedi_id"];
			
			return true;
		}
		

		public static function logout(){
			Logger::log("JEDI: Cerrando sesion");
			
			if(isset($_SESSION['userid']))
		        Logger::log("JEDI: Cerrando sesion para {$_SESSION['userid']}");
		    else
		        Logger::log("JEDI: Cerrando sesion generica");


			//por alguna razon, usar session_unset
			//no me deja volver a poner el valor de 
			//la instancia de nuevo en la sesion
			//asi que es mejor si borro individualmente
			//cada una

		    //session_unset ();

		    unset($_SESSION['ip']);
		    unset($_SESSION['pass']);
		    unset($_SESSION['ua']);
			unset($_SESSION['grupo']);
			unset($_SESSION['userid']);
			unset($_SESSION['sucursal']);
		}
		
		
		public static function isValidSession(){
			
			if(!isset($_SESSION['ip'])){
				Logger::log("JEDI_LOGIN: No hay ip en la variable de sesion !");
				return false;
			}
			
			
			if(isset($_SESSION['ip']) != getip()){
				Logger::log("JEDI_LOGIN: El ip de la sesion y el actual no coinciden !");
				return false;				
			}
			
			if(!isset($_SESSION['userid'])){
				Logger::log("JEDI_LOGIN: No hay userid en la variable de sesion !");
				return false;
			}
			
			//buscar a ese dude en la base de datos
			global $core_conn;

			$sql = "SELECT * FROM users WHERE (jedi_id = ? ) LIMIT 1;";

			$params = array( $_SESSION['userid'] );

			$rs = $core_conn->GetRow($sql, $params);

			if(count($rs)==0){
				Logger::log("JEDI_LOGIN: Ese jedi_id en la variable de sesion no existe WTF !");
				return false;	
			}

			//comparar los passwords
			if(!isset($_SESSION['pass'])){
				Logger::log("JEDI_LOGIN: no hay pwd en la sesion !");
				return false;				
			}
			
			if($_SESSION['pass'] != $rs["pass"]){
				Logger::log("JEDI_LOGIN: los passwords en sesion y en la base de datos no coinciden !");
				return false;				
			}
			
			
			if(!isset($_SESSION['ua'])){
				Logger::log("JEDI_LOGIN: no hay ua en la sesion !");
				return false;
			}

			if($_SESSION['ua'] != $_SERVER['HTTP_USER_AGENT']){
				Logger::log("JEDI_LOGIN: Los UA no coinciden, este we cambio de navegador WFT !");
				return false;				
			}
			
			if(!isset($_SESSION['grupo'])){
				Logger::log("JEDI_LOGIN: no hay grupo en la sesion !");
				return false;				
			}
			
			if($_SESSION['grupo'] != "JEDI"){
				Logger::log("JEDI_LOGIN: Este dude tiene un grupo diferente en la sesion, diferente de jedi");
				return false;	
			}

			
			return true;
		}
		
		
		
	}