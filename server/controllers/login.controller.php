<?php

class PermsissionDeniedException extends Exception { } 



interface IPermission
{
	function isLoggedIn();
}













abstract class LoginController implements IPermission
{
	
	function isLoggedIn()
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
		

		if( $_SESSION['pass'] !== $user->getPassword())
		{
			Logger::error("La constrasena en sesion es diferente en la BD!");
			return false;
		}

		return true;
	}
	


	function login($user_id, $password, $group_id )
	{
		$_SESSION['USER_ID'		] 	= $user_id; 
		$_SESSION['PASSWORD'	]	= $password;
		$_SESSION['HTTP_USER_AGENT']= $_SERVER["HTTP_USER_AGENT"];
		$_SESSION['USER_GROUP'	]	= $group_id;
	}
	


	function logout()
	{
	    unset($_SESSION['USER_ID']			);
	    unset($_SESSION['PASSWORD']			);
	    unset($_SESSION['HTTP_USER_AGENT']	);
		unset($_SESSION['USER_GROUP']		);
	}

}























class JediLoginController extends LoginController
{
	
	function isLoggedIn()
	{
		//@TODO see if the session is from some
		//other type of user other than jedi
		return parent::isLoggedIn();
	}


	function logout()
	{
		parent::logout();
	}


	function login($user, $password)
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



