<?php
require_once("interfaces/Sesion.interface.php");
/**
  *
  *
  *
  **/
	
class SesionController implements ISesion{

  	private static $_is_logged_in;
	private static $_current_user;
  	
    
	/**
 	 *
 	 *Regresa informacion sobre la sesion actual.
 	 *
 	 * @return id_caja int 
 	 * @return id_sucursal int El id_sucursal de la sucursal donde este usuario inico sesion en caso de haberlo hecho desde un mostraodr. Un gerente no tendra id_sucursal asociada a el dado que puede iniciar sesion desde cualquier lugar.
 	 * @return id_usuario int 
 	 **/
        public static function Actual(){
			Logger::log("actual()");

			if( !is_null(self::$_is_logged_in) && self::$_is_logged_in ){
							
				if(!is_null(self::$_current_user)){
	            	return array( "id_caja" => null, "id_sucursal" => null, "id_usuario" => self::$_current_user->getIdUsuario() );
				}
				$foo = self::getCurrentUser() ;

	            return array( "id_caja" => null, "id_sucursal" => null, "id_usuario" => $foo->getIdUsuario());
	
	
			}else{
            	return array( "id_caja" => null, "id_sucursal" => null, "id_usuario" => null);				
			}

	}
    
	

	/**
 	 *
 	 *Regresa un url de redireccion segun el tipo de usuario.
 	 *
 	 * @param auth_token string El token de autorizacion generado al iniciar la sesion
 	 * @return forward_to string La url de continuaci�n de acuerdo al id que cerr� sesi�n.
 	 **/
	public static function Cerrar
	(
		$auth_token = null
	)
	{  
		
  		//Logger::log("Cerrando sesion para toek {$auth_token}...");
		//Logger::warn("Falta borrar el token de la bd");
	  	self::$_is_logged_in = null;
		self::$_current_user = null;
		$sm = SessionManager::getInstance();
		$sm->SetCookie( 'at', 'deleted', 1, '/' );
  		
	}
  




	/**
 	 *
 	 * Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. 
 	 * Este m?todo no necesita de ning?n tipo de autenticaci?n. 
 	 * Si se detecta un tipo de usuario inferior a admin y no se ha llamado 
 	 * antes a api/sucursal/revisar_sucursal se regresar? un 403 Authorization 
 	 * Required y la sesi?n no se iniciar?.
 	 * Si el usuario que esta intentando iniciar sesion, esta descativado...
 	 * 403 Authorization Required supongo
 	 *
 	 * @param password string La contrasena del usuario.
 	 * @param usuario string El id de usuario a intentar iniciar sesion.
 	 * @param request_token bool Si se envia, y es verdadero, el seguimiento de esta sesi�n se har� mediante un token, de lo contrario se har� mediante cookies.
 	 * @return usuario_grupo int El grupo al que este usuario pertenece.
 	 * @return siguiente_url string La url a donde se debe de redirigir.
 	 * @return login_succesful	 bool Si la validaci�n del usuario es correcta.
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
			//Logger::warn("Credenciales invalidas para usuario {$usuario}");
			return array( "login_succesful" => false );
			
		}

		//ok user is ok, buscar su usuario en los tokens actuales
		$sesiones_actuales  = SesionDAO::search( new Sesion( array( "id_usuario" => $user->getIdUsuario() ) ) );
		
		if(sizeof($sesiones_actuales) > 0){
			////Logger::warn("Este usuario ya tiene sesiones actuales");
			
			foreach($sesiones_actuales	as $s ){
				
				try{
					SesionDAO::delete( $s );
					
				}catch(Exception $e){
					//Logger::error($e->getMessage());
					throw new InvalidDatabaseOperationException($e);
				}
			}
		}
		
		
		//si tiene un token actualmente que es valido, regenerar el token actualizar la fecha y darle el nuevo token
		$nueva_sesion = new Sesion();
		$nueva_sesion->setIdUsuario			( $user->getIdUsuario() 				);
		$nueva_sesion->setAuthToken			( self::GenerarAuthToken() 				);
		$nueva_sesion->setFechaDeVencimiento( date( "Y-m-d H:i:s", time() + 3600 ) 	);
		
		if(isset($_SERVER["HTTP_USER_AGENT"])){
			$nueva_sesion->setClientUserAgent	( $_SERVER["HTTP_USER_AGENT"] );
			
		}else{
			$nueva_sesion->setClientUserAgent	( "CLI" );
			
		}
			
		if(isset($_SERVER["REMOTE_ADDR"])){
			$nueva_sesion->setIp				( $_SERVER["REMOTE_ADDR"] );
			
		}else{
			$nueva_sesion->setIp				( "CLI" );			
			
		}

		
		
		try{
			SesionDAO::save( $nueva_sesion );
			////Logger::log("Setting _is_logged_in");
			
			
		}catch(Exception $e){
			//Logger::error( "Imposible escribir la sesion en la bd " );
			//Logger::error( $e->getMessage() );
			
			throw new InvalidDatabaseOperationException($e);
			
		}
		
		////Logger::log("Actual login...");
		self::login( $nueva_sesion->getAuthToken(), $nueva_sesion->getIdUsuario(), $user->getIdRol()  );
		
		////Logger::log("Setting _current_user");
		self::$_current_user = $user;
		self::$_is_logged_in = true;
		
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
 	 * @return en_linea json Arreglo de objetos que contendr�n la informaci�n de las sesiones activas
 	 **/
	public static function Lista
	(
		$id_grupo = null
	)
	{  
  		$out = array();
		$sesiones = SesionDAO::getAll(  );
		
		for ($i=0; $i < sizeof($sesiones); $i++) { 
			array_push( $out, $sesiones[$i]->asArray() );
		}
		
		return array(
			"numero_de_resultados" => sizeof($out),
			"resultados"			=> $out
		);
  		
	}




	/**
	  * Buscar a un usuario y passwor y regresar el objeto de ese usuario
	  *
	  *
	  **/
	public static function testLogin($user, $pass){
		//Logger::log("testLogin( {$user} )");

		//if( self::isLoggedIn() ) {
		//	//Logger::log( "Ya hay una sesion activa" );
		//	return UsuarioDAO::getByPK( $_SESSION['USER_ID'] );	
		//}

		//user is not logged in, look for him
		$user = UsuarioDAO::findUser( $user, $pass );


		if( $user === NULL ) {
			//Logger::warn("No se encontro el usuario " . $user);
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



	static function isLoggedIn(){
		
		//Logger::log("isLoggedIn() started...");
		
		if(isset(self::$_is_logged_in) && !is_null(self::$_is_logged_in) && self::$_is_logged_in){
			////Logger::log("isLoggedIn() already set ...");
			return true;
		}
		
		//Logger::log("getting session mananger");
		$sm = SessionManager::getInstance();
		
		$auth_token = $sm->GetCookie("at");
		
		if( is_null($auth_token) ) {
			//Logger::log("there is no auth token in the cookie");
			self::$_is_logged_in = false;
			return false;
		}
		
		////Logger::log("There is a session token in the cookie, lets test it.");
	
		$user = SesionDAO::getUserByAuthToken($auth_token);
	
		if(is_null($user)){
			//Logger::warn("auth_token was not found in the db, why is this?");
			self::$_is_logged_in = false;
			return false;
		}else{
			//Logger::log("auth_token validated, it belongs to user_id=" . $user->getIdUsuario());
			self::$_is_logged_in = true;
			return true;			
		}
		



	}
	


	private static function login($auth_token, $user_id, $rol_id ){
		//Logger::log("setting cookies");
		
		if(headers_sent()){
			//Logger::warn("Headers already sent while doing login (cookies).");
			return;
		}
		
		$sm = SessionManager::getInstance( );
		

		
		$sm->SetCookie( 'at',  $auth_token, 	time()+60*60*24, '/' );
		$sm->SetCookie( 'rid', $rol_id, 		time()+60*60*24, '/' );
		$sm->SetCookie( 'uid', $user_id, 		time()+60*60*24, '/' );

	}
	





	public static function getCurrentUser(  ){

		if( !is_null(self::$_current_user) ){
			return self::$_current_user;
		}		


		$sm = SessionManager::getInstance();
		$auth_token = $sm->GetCookie( "at" );
		
		self::$_current_user = null;
		
		//Logger::log("getCurrentUser()");
		
		//there is no authtoken cookie
		if(!is_null($auth_token)){
			//Logger::log("cookie");			
			self::$_current_user = SesionDAO::getUserByAuthToken( $auth_token );	
		}
		

		//there is authtoken in the POST message
		if( isset($_POST["at"]) && !is_null($_POST["at"]) ){
			//Logger::log("post");
			self::$_current_user = SesionDAO::getUserByAuthToken( $_POST["at"] );
		}
		
		//there is authtoken in the GET message
		if(isset($_GET["at"]) && !is_null($_GET["at"])){
			//Logger::log("get");
			self::$_current_user = SesionDAO::getUserByAuthToken( $_GET["at"] );
		}
		
		return self::$_current_user;

			
			
	}


}
