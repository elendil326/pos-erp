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





	private $main_menu_json;



	/**
	 *
	 *	Crear los menues gracias a un json,
	 *  en un futuro este json puede
	 *  estar en la base de datos y ser diferente 
	 *  para cada usuario ! nice ! 
	 **/
	private function createMainMenu()
	{
		$this->main_menu_json = '
				{
    "main_menu": [
        {
            "title": "Autorizaciones",
            "url": "autorizaciones.php",
            "children": [
                {
                    "title": "Lista",
                    "url": ""
                }
            ]
        },
        {
            "title": "Cargos y abonos",
            "url": "cargos_y_abonos.php",
            "children": [
                {
                    "title": "",
                    "url": ""
                }
            ]
        },
        {
            "title": "Clientes",
            "url": "clientes.php",
            "children" : [
            	{
            		"title" : "Nuevo",
            		"url"   : "clientes.nuevo.php"
            	}
            ]
        },
        {
            "title": "Compras",
            "url": "compras.php"
        },
        {
            "title": "Consignaciones",
            "url": "consignaciones.php"
        },
        {
            "title": "Contabilidad",
            "url": "contabilidad.php"
        },
        {
            "title": "Documentos",
            "url": "documentos.php"
        },
        {
            "title": "Efectivo",
            "url": "efectivo.php"
        },
        {
            "title": "Empresas",
            "url": "empresas.php",
            "children" : [
            	{
            		"title" : "Nueva",
            		"url" 	: "empresas.nuevo.php"
            	}
            ]
        },
        {
            "title": "Impuestos y retenciones",
            "url": "impuestos.php"
        },
        {
            "title": "Inventarios",
            "url": "inventario.php"
        },
        {
            "title": "Productos",
            "url": "productos.php"
        },
        {
            "title": "Personal y agentes",
            "url": "personal.php"
        },
        {
            "title": "Proveedores",
            "url": "proveedores.php"
        },
        {
            "title": "Reportes",
            "url": "reportes.php"
        },
        {
            "title": "Servicios",
            "url": "servicios.php"
        },
        {
            "title": "Sucursales",
            "url": "sucursales.php"
        },
        {
            "title": "Transportacion y fletes",
            "url": "transportacion.php"
        },
        {
            "title": "Ventas",
            "url": "ventas.php"
        }
    ]
}
				';
		
	}


	function bootstrap()
	{

		$this->createMainMenu();

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



			<link rel="stylesheet" type="text/css" href="http://api.caffeina.mx/ext-latest/resources/css/ext-all.css" /> 
		    <script type="text/javascript" src="http://api.caffeina.mx/ext-latest/adapter/ext/ext-base.js"></script> 
		    <script type="text/javascript" src="http://api.caffeina.mx/ext-latest/ext-all.js"></script> 

			<link type="text/css" rel="stylesheet" href="../../../css/basic.css"/>
			<script type="text/javascript" src="./gerencia.js"></script>
		</head>
		<body class="">
		<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;"></div>
		<div class="devsitePage">
			<div class="menu">
				<div class="content">
					<a class="logo" href="index.php">
						<img class="img" src="../../../media/N2f0JA5UPFU.png" alt="" width="166" height="17"/>
					</a>


					<a class="l" href="./configuracion.php">Configuracion</a>
					<a class="l" href="./&close_session">Salir</a>

					<a class="l">
						<img style="margin-top:8px; display: none;" id="ajax_loader" src="../../../media/loader.gif">
					</a>


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

							<?php
							################ Main Menu ################

								$mm = json_decode( $this->main_menu_json );

								foreach ( $mm->main_menu as $item )
								{

									echo "<li ";

									if(isset( $item->children ))
									{
										echo "class='withsubsections'";
									}

									echo "><a href='". $item->url  ."'><div clas='navSectionTitle'>" . $item->title . "</div></a>";

									if(isset( $item->children ))
									{

										foreach( $item->children as $subitem )
										{
											echo '<ul class="subsections">';
											echo "<li>";
											echo '<a href="'. $subitem->url .'">' . $subitem->title . '</a>';
											echo "</li>";
											echo "</ul>";
										}

									}

									echo "</li>";

								}

							################ Main Menu ################
							?>

						</div>
						<!--
						<ul id="navsubsectionpages">
							<li>asdf</li>
						</ul>
						-->
					</div>
					<div id="bodyText" class="bodyText">
						<div class="header">
							<div class="content">
							<!-- ----------------------------------------------------------------------
											CONTENIDO
								 ---------------------------------------------------------------------- -->

								 <?php
									foreach( $this->components as $cmp )
									{
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