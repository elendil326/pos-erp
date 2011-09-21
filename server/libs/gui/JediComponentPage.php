<?php 

class JediComponentPage extends StdComponentPage{

	private $permisos_controller;







	function __construct()
	{

		parent::__construct();


		//vamos a ver si tengo permiso para 
		//crear una pagina jedi
		$jedi_login = new JediLoginController();

		
		//user is logged in, go ahead
		if($jedi_login->isLoggedIn()) return;
		

		//ok no esta loggeado, pero 
		//vamos a ver si se quiere loggear
		if(
				isset($_POST["jedi_login"]	) 
			&& 	$_POST["jedi_login"] == 1
			&& 	isset( $_POST["user"] 		)
			&& 	isset( $_POST["password"] 	)
		)
		{
			//user wants to login
			if($jedi_login->login($_POST["user"], $_POST["password"]))
			{
				//login was succesful, keep building this object
				return;

			}else{
				//unsuccessful login
				$this->dieWithLogin("Credenciales invalidas");	

			}

		}else{
			$this->dieWithLogin();

		}

	

	}//__construct()











	/**
      * End page creation and ask for login
      * optionally sending a message to user
	  **/
	private function dieWithLogin($message = null)
	{
		$login_cmp = new LoginComponent();

		if( $message != null )
		{
			self::addComponent(new MessageComponent($message));				
		}

		self::addComponent($login_cmp);
		self::render();
		exit();
	}

}