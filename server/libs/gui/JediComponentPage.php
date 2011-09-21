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
		if($jedi_login->isLoggedIn()) 
		{
			//usuario esta loggeado, 
			//vamos a ver si quiere 
			//cerrar sesion
			if(isset($_GET["close_session"]))
			{
				//si quiere cerrar la sesion ! 
				$jedi_login->logout();
				die(header("Location: ./?bye"));
			}

			return $this->bootstrap();
				
		}
		

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
				return $this->bootstrap();

			}else{
				//unsuccessful login
				$this->dieWithLogin("Credenciales invalidas");	

			}

		}else{
			$this->dieWithLogin();

		}

		//there should be no path ending here!
		Logger::error("JEDI component page : there should be no path ending here!");

	}//__construct()





	function bootstrap()
	{
		$m = new MenuComponent();
		$m->addItem("Cerrar sesion", "./?close_session");
		$m->addItem("Instancias", "instancias.php");
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


	/**
	  *
	  *
	  **/
	function render()
	{
		parent::render();
	}

}




