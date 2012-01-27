<?php
require_once("interfaces/Sesion.interface.php");
/**
  *
  *
  *
  **/
	
class SesionController implements ISesion{

  
  	
	

	/**
 	 *
 	 *Regresa un url de redireccion segun el tipo de usuario.
 	 *
 	 * @param auth_token string El token de autorizacion generado al iniciar la sesion
 	 * @return forward_to string La url de continuación de acuerdo al id que cerró sesión.
 	 **/
	public static function Cerrar
	(
		$auth_token = null
	)
	{  
  		
  		
	}
  




	/**
 	 *
 	 *Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. Este m?todo no necesita de ning?n tipo de autenticaci?n. 
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar? un 403 Authorization Required y la sesi?n no se iniciar?.
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required supongo
 	 *
 	 * @param password string La contraseña del usuario.
 	 * @param usuario string El id de usuario a intentar iniciar sesión.
 	 * @param request_token bool Si se envía, y es verdadero, el seguimiento de esta sesión se hará mediante un token, de lo contrario se hará mediante cookies.
 	 * @return usuario_grupo int El grupo al que este usuario pertenece.
 	 * @return siguiente_url string La url a donde se debe de redirigir.
 	 * @return login_succesful	 bool Si la validación del usuario es correcta.
 	 * @return auth_token string El token si es que fue solicitado.
 	 **/
	public static function Iniciar
	(
		$password, 
		$usuario, 
		$request_token = null
	)
	{  

		//user is not logged in, look for him
		$user = UsuarioDAO::findUser( $usuario, $password );


		if( $user === NULL ) {
			Logger::warn("Credenciales invalidas para usuario {$user}");
			return array( "login_succesful" => false );
			
		}

		//ok user is ok, buscar su usuario en los tokens actuales
		$sesiones_actuales  = SesionDAO::search( new Sesion( array( "id_usuario" => $user->getIdUsuario() ) ) );
		
		if(sizeof($sesiones_actuales) > 0){
			Logger::warn("Este usuario ya tiene sesiones actuales");
			
			foreach($sesiones_actuales	as $s ){
				
				try{
					SesionDAO::delete( $s );
					
				}catch(Exception $e){
					Logger::error($e);
					throw $e;
				}
			}
		}
		
		
		//si tiene un token actualmente que es valido, regenerar el token actualizar la fecha y darle el nuevo token
		$nueva_sesion = new Sesion();
		$nueva_sesion->setIdUsuario			( $user->getIdUsuario() 				);
		$nueva_sesion->setAuthToken			( self::GenerarAuthToken() 				);
		$nueva_sesion->setFechaDeVencimiento( date( "Y-m-d H:i:s", time() + 3600 ) 	);
		$nueva_sesion->setClientUserAgent	( $_SERVER["HTTP_USER_AGENT"]			);
		$nueva_sesion->setIp				( $_SERVER["REMOTE_ADDR"] 				);
		
		
		try{
			SesionDAO::save( $nueva_sesion );
			
		}catch(Exception $e){
			Logger::error( "Imposible escribir la sesion en la bd " );
			Logger::error( $e );
			throw new Exception("Imposible iniciar la sesion");
		}
		
		self::login( $nueva_sesion->getAuthToken(), $nueva_sesion->getIdUsuario(), $user->getIdRol()  );
		
		return array( "auth_token" => $nueva_sesion->getAuthToken(), "login_succesful" => true );
	}
  

	private static function GenerarAuthToken
	(
		$salt = null,
		$vencimiento = null
	){
		return md5( rand(  ) );
	}




	/**
 	 *
 	 *Obtener las sesiones activas.
 	 *
 	 * @param id_grupo int Obtener la lista de sesiones activas para un grupo de usuarios especifico.
 	 * @return en_linea json Arreglo de objetos que contendrán la información de las sesiones activas
 	 **/
	public static function Lista
	(
		$id_grupo = null
	)
	{  
  
  
	}




	/**
	  * Buscar a un usuario y passwor y regresar el objeto de ese usuario
	  *
	  *
	  **/
	public static function testLogin($user, $pass)
	{
		Logger::log("testLogin( {$user} )");

		if( self::isLoggedIn() ) {
			Logger::log( "Ya hay una sesion activa" );
			return UsuarioDAO::getByPK( $_SESSION['USER_ID'] );	
		}

		//user is not logged in, look for him
		$user = UsuarioDAO::findUser( $user, $pass );


		if( $user === NULL ) {
			Logger::warn("No se encontro el usuario " . $user);
			return NULL;	
		}

		//ok user is ok, log him in
		self::login( $user->getIdUsuario(), $pass, $user->getIdRol() );

		//dispatch
		self::dispatchUser($user->getIdRol());
		
		
	}


	private static function dispatchUser( $group ){
		switch($group){
			case 1: die( header( "Location: gerencia/" ) );
		}
		
	}




	static function isLoggedIn()
	{
		Logger::log("isLoggedIn() started");

		$sm = SessionManager::getInstance();
		$auth_token = $sm->GetCookie("at");
		
		if( !is_null($auth_token) ) {
			Logger::log("There is a session token in the cookie, lets test it.");
			
			$user = SesionDAO::getUserByAuthToken($auth_token);
			
			if(is_null($user)){
				Logger::warn("auth_token was not found in the db, why is this?");
				return false;
			}else{
				Logger::log("auth_token validated, it belongs to user_id=" . $user->getIdUsuario());
				return true;			
			}
		}

		return false;


		

		/*
		//regresar falso si alguno de estos no esta 
		if(
				!isset($_SESSION['USER_ID']			)
			|| 	!isset($_SESSION['PASSWORD']		)
			|| 	!isset($_SESSION['HTTP_USER_AGENT']	)
			|| 	!isset($_SESSION['USER_ROL']		)
		) return false;


		if( $_SESSION['HTTP_USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']  )
		{
			Logger::error("El user agent en sesion es diferente al que envio la peticion");
			return false;
		}



		//ok, los valores estan ahi, vamos a buscar a ese usuario
		$user = UsuarioDAO::getByPK( $_SESSION['USER_ID'] );

		if($user === null)
		{
			Logger::error("El usuario que esta en sesion ya no existe en la BD.");
			return false;
		}


		if($user->getActivo() === false)
		{
			Logger::error("El usuario que esta en sesion esta desactivado en la BD.");
			return false;
		}	
		

		if( $_SESSION['PASSWORD'] !== $user->getPassword())
		{
			Logger::error("La constrasena en sesion es diferente en la BD!");
			return false;
		}

		return true;
		* */
	}
	


	private static function login($auth_token, $user_id, $rol_id )
	{
		
		$sm = SessionManager::getInstance();
		$sm->SetCookie( 'at',  $auth_token, time()+60*60*24, '/' );
		$sm->SetCookie( 'rid', $user_id, time()+60*60*24, '/' );
		$sm->SetCookie( 'uid', $rol_id, time()+60*60*24, '/' );
		
		/*
		Logger::warn("Iniciando sesion");

		$_SESSION['USER_ID'			] 	= $user_id; 
		$_SESSION['PASSWORD'		]	= md5($password);
		
		if(isset( $_SERVER["HTTP_USER_AGENT"] ))
			$_SESSION['HTTP_USER_AGENT'	]	= $_SERVER["HTTP_USER_AGENT"];
		else
			$_SESSION['HTTP_USER_AGENT'	]	= "NOT_SET";
			
		$_SESSION['USER_ROL'		]	= $rol_id;
		*/
	}
	


	public static function logout()
	{

		Logger::warn("Cerrando sesion");

		$sm = SessionManager::getInstance();
		$sm->SetCookie( 'at', 'deleted', 1, '/' );
		
	    /*unset($_SESSION['USER_ID']			);
	    unset($_SESSION['PASSWORD']			);
	    unset($_SESSION['HTTP_USER_AGENT']	);
		unset($_SESSION['USER_ROL']			);*/
	}




	public static function getCurrentUser(){
		

		Logger::log("SesionController::getCurrentUser(  )");
		
		if(self::isLoggedIn()){
			$sm = self::getSessionManagerInstance();
			$auth_token = $sm->GetCookie( "at" );
			
			//there is authtoken cookie
			if(!is_null($auth_token)){
				return SesionDAO::getUserByAuthToken( $auth_token );				
			}
			

			//there is authtoken in the POST message
			if(!is_null($_POST["auth_token"])){
				return SesionDAO::getUserByAuthToken( $_POST["auth_token"] );
			}
			
			//there is authtoken in the GET message
			if(!is_null($_GET["auth_token"])){
				return SesionDAO::getUserByAuthToken( $_GET["auth_token"] );
			}
			
		}else{
			return NULL;
			
		}
			
		
		/*
		if(isset($_GET["auth_token"])) {

			$u = SesionDAO::getCurrentUser($_GET["auth_token"]);
			return $u->getIdUsuario();
		}
		
		if(isset($_POST["auth_token"])) {

			$u = SesionDAO::getCurrentUser($_POST["auth_token"]);
			return $u->getIdUsuario();
		}
		
		if(self::isLoggedIn())
			return $_SESSION['USER_ID'];
		else
			return null;

		**/			
	}


}
