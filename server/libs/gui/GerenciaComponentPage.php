<?php


class GerenciaComponentPage extends StdComponentPage{

	private $permisos_controller;


	function __construct()
	{

		parent::__construct();

		//vamos a ver si estamos loggeados
		$permisos_controller = new GerenciaLoginController();


		//user is logged in, go ahead
		if($permisos_controller->isLoggedIn()) 
		{
			
			//usuario esta loggeado, 
			//vamos a ver si quiere 
			//cerrar sesion
			if(isset($_GET["close_session"]))
			{
				
				//si quiere cerrar la sesion ! 
				$permisos_controller->logout();
				die(header("Location: ./&bye"));
			}

			return $this->bootstrap();
				
		}


		//ok no esta loggeado,
		//vamos a ver si se quiere 
		//iniciar sesion
		if(
				isset($_POST["do_login"]	) 
			&& 	$_POST["do_login"] == 1
			&& 	isset( $_POST["user"] 		)
			&& 	isset( $_POST["password"] 	)
		)
		{
			//user wants to login
			if($permisos_controller->login($_POST["user"], $_POST["password"]))
			{
				//login was succesful, keep building this object
				return $this->bootstrap();

			}else{
				//unsuccessful login
				$this->dieWithLogin("Credenciales invalidas");	

			}

		}else{
			$this->dieWithLogin();

		}

	}//__construct



	function bootstrap()
	{
		$m = new MenuComponent();
		$m->addItem("Home", "home.php");
		$m->addItem("Cerrar sesion", "./&close_session");
		self::addComponent( $m );
	}


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
		parent::render();
		exit();
	}
}