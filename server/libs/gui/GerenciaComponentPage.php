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
				// login was succesful,
				// lets refresh the page
				// se we change from POST(login)
				// to GET
				die(header("Location: ."));
				

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
		/*
		$m = new MenuComponent();
		$m->addItem("Home", "home.php");
		$m->addItem("Cerrar sesion", "./&close_session");
		self::addComponent( $m );
		*/
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


	function render()
	{
		?>

		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
		<head>

		<title>POS</title>


		<link type="text/css" rel="stylesheet" href="../../../css/basic.css"/>


		</head>
		<body class="safari4 mac Locale_en_US">

		<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;"></div>

		<div class="devsitePage">
			<div class="menu">
				<div class="content">
					
					<a class="logo" href="/">
						<img class="img" src="../../../media/N2f0JA5UPFU.png" alt="" width="166" height="17"/>
					</a>


					<a class="l" href="./&close_session">Salir</a>


					<div class="search">
						<form method="get" action="/search">
							<div class="uiTypeahead" id="u272751_1">
								<div class="wrap">
									<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
									<div class="innerWrap">
										<span class="uiSearchInput textInput">
										<span>
										
										<input 
											type="text" 
											class="inputtext DOMControl_placeholder" 
											name="selection" 
											placeholder="Buscar" 
											autocomplete="off" 
											onfocus="" 
											spellcheck="false"
											title="Search Documentation / Apps"/>
										<button type="submit" title="Search Documentation / Apps">
										<span class="hidden_elem">
										</span>
										</button>
										</span>
										</span>
									</div>
								</div>
											
								


							</div>
						</form>
					</div>
					<div class="clear">
					</div>
				</div>
			</div>
			<div class="body nav">
				<div class="content">
					<div id="bodyMenu" class="bodyMenu">
						<div class="toplevelnav">
							<ul>

							<!-- inactivo -->
							<li>
							<a href="api_doc.php?cat=4">
								<div class="navSectionTitle">Menu1</div>
							</a>
							</li>

							<!-- activo -->
							<li class="active withsubsections">
								<a class="selected" href="api_doc.php?cat=7">
								<div class="navSectionTitle">MENU2</div>
								</a>

								<ul class="subsections">							
								<li><a href="?&cat=7&m=187">abono/editar</a></li><li><a href="?&cat=7&m=188">abono/eliminar</a></li>
								</ul></li>

												
								
							</ul>
						</div>

						<ul id="navsubsectionpages">
							
						</ul>
					</div>
					<div id="bodyText" class="bodyText">
						<div class="header">
							<div class="content">
							<!-- ----------------------------------------------------------------------
											CONTENIDO
								 ---------------------------------------------------------------------- -->
								 <?php
									foreach( $this->components as $cmp ){
										echo $cmp->renderCmp();
									}
								 ?>
								
								<!--
								<div class="breadcrumbs">
									<a href=".">POS ERP</a> 
									&rsaquo; <a href=".">Cargos y abonos</a>							
								</div>
								-->
									
							</div>
						</div>


						<div class="mtm pvm uiBoxWhite topborder">
							<div class="mbm">
								
							</div>
							<abbr class="timestamp">Generado <?php echo date("r",time()); ?></abbr>
						</div>

					</div>

					<div class="clear">
					</div>

				</div>
			</div>
			<div class="footer">
				<div class="content">
					
					<div class="copyright">
					Caffeina
					</div>

					<div class="links">
						<a href="">About</a>
						<a href="">Platform Policies</a>
						<a href="">Privacy Policy</a>
					</div>
				</div>
			</div>

			<div id="fb-root"></div>
			
			<div id="fb-root"></div>
			
		</div>

		</body>
		</html>
		<?php
	}


}