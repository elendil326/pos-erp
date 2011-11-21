<?php


/*  *********************************************************************************
	*********************************************************************************
	*	DEPRECTED: PLEASE DO NOT USE THIS FUNCTIONS FROM NOW ON 					*
	********************************************************************************* 
	********************************************************************************* */


class PermsissionDeniedException extends Exception { } 



interface IPermission
{
	static function isLoggedIn();
}













abstract class LoginController implements IPermission
{
	
	static function isLoggedIn()
	{

		//regresar falso si alguno de estos no esta 
		if(
				!isset($_SESSION['USER_ID']			)
			|| 	!isset($_SESSION['PASSWORD']		)
			|| 	!isset($_SESSION['HTTP_USER_AGENT']	)
			|| 	!isset($_SESSION['USER_GROUP']		)
		) return false;


		if( $_SESSION['HTTP_USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']  )
		{
			Logger::error("El user agent en sesion es diferente al que envio la peticion");
			return false;
		}


		if($_SESSION['USER_GROUP'] === "JEDI")
		{
			return true;				
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
	}
	


	function login($user_id, $password, $group )
	{
		Logger::warn("Iniciando sesion");
		$_SESSION['USER_ID'		] 	= $user_id; 
		$_SESSION['PASSWORD'	]	= $password;
		$_SESSION['HTTP_USER_AGENT']= $_SERVER["HTTP_USER_AGENT"];
		$_SESSION['USER_GROUP'	]	= $group;

	}
	


	function logout()
	{
		Logger::warn("Cerrando sesion");
	    unset($_SESSION['USER_ID']			);
	    unset($_SESSION['PASSWORD']			);
	    unset($_SESSION['HTTP_USER_AGENT']	);
		unset($_SESSION['USER_GROUP']		);
	}


	public static function getCurrentUser(){
		if(self::isLoggedIn())
			return $_SESSION['USER_ID'];
		else
			return null;
	}

}











class GerenciaLoginController extends LoginController{
	
	function login($user_id, $password, $group = 1)
	{
		$u = new Usuario();
		$u->setIdUsuario 	( $user_id  );
		$u->setPassword 	( md5( $password ) );
		
		$res = UsuarioDAO::search( $u );
		
		if( sizeof($res) == 1 && (1 == $res[0]->getIdRol()) ){

			parent::login( $user_id, md5($password), "0" );
			return true;	

		}else{

			Logger::warn("Intento de inicio de sesion invalido para ( ".$user_id." )");
			return false;

		}

		
	}
}












class JediLoginController extends LoginController
{
	
	static function isLoggedIn()
	{
		//@TODO see if the session is from some
		//other type of user other than jedi
		return parent::isLoggedIn();
	}


	function logout()
	{
		parent::logout();
	}


	function login($user, $password, $group = "JEDI")
	{

		global $POS_CONFIG;
		
		$sql = "SELECT id_user FROM users WHERE ( email = ? AND password = ?) LIMIT 1;";
		
		$params = array( $user, md5($password) );

		$rs = $POS_CONFIG["CORE_CONN"]->GetRow($sql, $params);
		
		if(count($rs) === 0)
		{
			Logger::error("Jedi login fallido para el usuario : " . $user);
			return false;
		}

		parent::login( $rs['id_user'], md5($password), "JEDI");
		Logger::log("Acceso autorizado para jedi " . $user);
		return true;
	}
}



