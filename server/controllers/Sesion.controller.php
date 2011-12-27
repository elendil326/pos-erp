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
 	 *Regresa un url de redireccion seg?n el tipo de usuario.
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
		$user = UsuarioDAO::findUser( $user, $pass );

		if( $user === NULL ) throw new Exception("Credenciales invalidas");

		//ok user is ok, log him in
		self::login( $user->getIdUsuario(), $pass, $user->getIdRol() );
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
		Logger::log("Probando usuario y contrasena");

		if( self::isLoggedIn() ) return UsuarioDAO::getByPK( $_SESSION['USER_ID'] );

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

		/*
		if($_SESSION['USER_ROL'] === "JEDI")
		{
			return true;				
		}*/


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
	}
	


	private static function login($user_id, $password, $rol_id )
	{
		
		Logger::warn("Iniciando sesion");

		$_SESSION['USER_ID'			] 	= $user_id; 
		$_SESSION['PASSWORD'		]	= md5($password);
		$_SESSION['HTTP_USER_AGENT'	]	= $_SERVER["HTTP_USER_AGENT"];
		$_SESSION['USER_ROL'		]	= $rol_id;

	}
	


	public static function logout()
	{

		Logger::warn("Cerrando sesion");

	    unset($_SESSION['USER_ID']			);
	    unset($_SESSION['PASSWORD']			);
	    unset($_SESSION['HTTP_USER_AGENT']	);
		unset($_SESSION['USER_ROL']			);
	}


	public static function getCurrentUser(){
		return 1;
		if(self::isLoggedIn())
			return $_SESSION['USER_ID'];
		else
			return null;
	}

  }
