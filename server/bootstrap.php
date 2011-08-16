<?php

	# <debug>
	//if(defined("I_AM_SUCURSAL") && I_AM_SUCURSAL) sleep(3);
	# </debug>


	# *******************************
	# Definiciones
	# *******************************
	define('POS_SEMANA', 1);
	define('POS_MES', 1);

	//requerir la configuracion
    require_once('config.php');

	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);
	
	//logger
	require_once("logger.php");


	//nombre de la galleta
	//session_set_cookie_params ( int $lifetime [, string $path [, string $domain [, bool $secure = false [, bool $httponly = false ]]]] )
	session_name("POS_ID");

	session_set_cookie_params ( 0  , '/' );

	function no_instance()
	{
		?>
		<head>
			<style>
			div.gs-container {
				width:930px;
				margin-bottom:20px;
			}


			div.container,div.container * {
				float:left;
				display:block;
				padding:0;
				margin:0;
				font-size:11px;
			}
			
			div.gs-container div.notify {
				-moz-border-radius: 0 0 5px 5px;
				-webkit-border-radius: 0 0 5px 5px;
				border-radius: 0 0 5px 5px;
			}

			div.gs-container div.get-started-title {
				-moz-border-radius:5px 5px 0 0;
				-webkit-border-radius:5px 5px 0 0;
				border-radius:5px 5px 0 0;
			}



			div.get-started-title {
				width:915px;
				background-color:#64B23B;
				padding:5px 5px 5px 10px;
			}


			div.get-started-title h2 {
				font:bold 14px/16px Arial,sans-serif;
				color:#FFF;
				margin:0;
				padding:0;
				border-width:0!important;
				width:auto!important;
			}



			em.gs-close {
				width:17px;
				height:16px;
				background-position:-240px -65px;
				float:right;
				margin-left:5px;
				cursor:pointer;
			}



			em.icons {
			background: transparent url(/common/images/modules/icons-07661f.png) no-repeat 0 0;
			float: left;
			}


			div.get-started-title a:link, div.get-started-title a:visited, div.get-started-title a:active, div.get-started-title a:hover {
			color: white;
			float: right;
			}a:link, a:visited, a:active, a:hover {
			text-decoration: none;
			color: #048FC2;
			}



			div.get-started-title a span {
			visibility: hidden;
			color: #D4EF74;
			margin-right: 5px;
			}



			div.notify {
			position: relative;
			padding: 10px;
			width: 906px;
			border-width: 2px;
			border-style: solid;
			margin-bottom: 15px;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			-moz-box-shadow: 1px 1px 3px #DDD;
			-webkit-box-shadow: 1px 1px 3px #DDD;
			box-shadow: 1px 1px 3px #ddd;
			border-radius: 5px;
			}



			div.get-started {
			border: 1px solid #C1C1C1;
			border-top-width: 0;
			width: 908px;
			background-color: #F9FEF6;
			}



			div.notify em {
			background-color: transparent;

			background-repeat: no-repeat;
			width: 32px;
			height: 32px;
			float: none;
			position: absolute;
			top: 10px;
			left: 10px;
			}


			div.get-started em {
			display: none;
			}


			em {
			font-style: normal;
			}


			div.get-started div.message {
			padding: 0;
			width: 905px;
			color: #555;
			}


			div.get-started div.message * {
			color: #555;
			}


			div.notify div.message * {
			font: normal 11px/15px Tahoma,sans-serif;
			color: #333;
			float: none;
			}


			div.document p, div.document p strong, div.document br {
			float: none;
			clear: both;
			line-height: 140%;
			color: #333;
			}


			div.document p {
			margin-bottom: 10px;
			}





			div.notify div.message ul, div.notify div.message ol {
			clear: both;
			float: none;
			margin-bottom: 10px;
			list-style-position: outside;
			list-style-type: disc;
			margin-left: 16px;
			}

			div.get-started div.message * {
			color: #555;
			}

			ol, ul {
			list-style: none;
			}
			</style>
		</head>
		<body>
		<!-- - - - - - - - - - - - - - - - - - - - - - - - -  Getting Started - - - - - - - - - - - - - - - - - - - - - - - - -->
		<div id="content" style="margin : 55px auto; width : 80%">
		<div class="container"  >
		<div class="document summary" >
		<div class="gs-container" style=""  >
			<div class="get-started-title">
				<h2>Error !</h2>

			</div>
			<div class="notify get-started">
				<em>
				&nbsp; </em>
				<div class="message">
					<p>
						 No se proporciono un numero de instancia.
					</p>
	

				</div>
			</div>
		</div>
		</div>
		</div>
		</div>						
		</body>
		<?php
	}
	
	# *******************************
	# Bootstrap
	# *******************************
	try{
		$ss = session_start (  );
	}catch(Exception $e){
		Logger::log($e);
		die();
	}


	if(!$ss){
		echo '{"success": false,"reason": "Imposible iniciar sesion." }';
		Logger::log("Imposible iniciar sesion !");
		die();
	}

	require_once('utils.php');


	
	if(	
		defined("I_AM_GET_RESOURCE") 
		&& I_AM_GET_RESOURCE
		#&& $_SESSION['grupo']  == "JEDI"
	){
		return;
	}
	
	
	//tengo el id de instancia ?
	if(	!isset($_GET["i"]) 
		&& !isset($_SESSION["INSTANCE_ID"])
	){



		//la pagina de login, pone I_AM_LOGIN en verdadero, 
		//es una buena manera de saber si vengo del login
		//si vengo del login
		if(defined("I_AM_LOGIN") && I_AM_LOGIN) {
			Logger::log("I_AM_LOGIN: There is no instance number nowhere !!" );
			die( no_instance() );
			
		}
		
		
		if(defined("I_AM_SUCURSAL") && I_AM_SUCURSAL) {
			Logger::log("I_AM_SUCURSAL: There is no instance number nowhere !!" );				
			die( no_instance() );
				
		}

		if(defined("I_AM_CLIENTE") && I_AM_CLIENTE) {
			Logger::log("I_AM_CLIENTE: There is no instance number nowhere !!" );				
			die('<div align=center><img src="media/intro.png"></div>');
		}
		
		
		if(defined("I_AM_PROXY") && I_AM_PROXY) {
			Logger::log("I_AM_PROXY: There is no instance number nowhere !!" );				
			die( '{"success": false , "reason": "Accesso denegado" , "reboot" : true, "furthermore" : "no-instance" }');
		} 

		Logger::log("SOURCE : " . $_SERVER['REQUEST_URI']);
		Logger::log("     There is no instance number nowhere, sending header to log.php !!" );
		
		die(header("Location: ./log.php"));
		
	}
	
	
	
	//no esta la de sesion, pero esta el get
	if(!isset($_SESSION["INSTANCE_ID"]) && isset($_GET["i"])){
		Logger::log("No hay instancia en la sesion, pero si como parametro. Insertando en sesion." );
		$_SESSION["INSTANCE_ID"] = $_GET["i"];
	}



	//estan las dos, pero son diferentes
	if(isset($_SESSION["INSTANCE_ID"]) && isset($_GET["i"])
		&& $_SESSION["INSTANCE_ID"] != $_GET["i"]
		){
		Logger::log("SE HA CAMBIADO LA INSTANCIA !!!!", 3 );
		
		//cerrar la sesion actual
		require("controller/login.controller.php");
		logOut(false);
		
		echo "<script>window.location = '.';</script>";
		$_SESSION["INSTANCE_ID"] = $_GET["i"];
	}





	//conectarme a la base de datos pos_core
	require('db/PosCoreDBConnection.php');
	
	//buscar si existe una instancia con ese id
	$sql = "SELECT * FROM instances WHERE (instance_id = ? ) LIMIT 1;";
	$params = array(  $_SESSION["INSTANCE_ID"] );

	$rs = $core_conn->GetRow($sql, $params);
	
	if(count($rs)==0){
		//no existe esta instancia !!
		Logger::log("The instance {$_SESSION["INSTANCE_ID"]} does not exist !!!",3);
		die("this instance does not exist !");
	}

	
	# titulo de la aplicacion para las paginas html
	define("_POS_HTMLTITLE", "Papas Supremas");

	define('POS_MAX_LIMITE_DE_CREDITO', 	$rs['MAX_LIMITE_DE_CREDITO']);
	define('POS_MAX_LIMITE_DESCUENTO', 		$rs['MAX_LIMITE_DESCUENTO']);

	define('POS_PERIODICIDAD_SALARIO', POS_SEMANA);

	# habilitar o deshabilitar el uso de mapas en la aplicacion
	define('POS_ENABLE_GMAPS', 				$rs['ENABLE_GMAPS']);

	#estilo para las fechas
	define('POS_DATE_FORMAT', 				$rs['DATE_FORMAT']);
	
	define('DB_USER',       				$rs['DB_USER']);
	define('DB_PASSWORD',   				$rs['DB_PASSWORD']);
	define('DB_NAME',       				$rs['DB_NAME']);
	define('DB_DRIVER',     				$rs['DB_DRIVER']);
	define('DB_HOST',       				$rs['DB_HOST']);
	define('DB_DEBUG',      				$rs['DB_DEBUG']);

	
	# <DEPRECATED>
	#	define("HEARTBEAT_METHOD_TRIGGER", 		$rs['HEARTBEAT_METHOD_TRIGGER'] == "true");
	# </DEPRECATED>
	
	define("HEARTBEAT_INTERVAL", 			$rs['HEARTBEAT_INTERVAL']);


	define( 'DEMO', 						$rs['DEMO']);

	#leer las caracteristicas del sistema
	$sql = "SELECT * FROM core_functionality WHERE ( instance_id = ? ) ;";
	$params = array(  $_SESSION["INSTANCE_ID"] );

	$rs = $core_conn->GetRow($sql, $params);

	if(count($rs)==0){
		//no existe esta instancia !!
		Logger::log("This core_functionality for this instance are not  set !!!",3);
		die("This core_functionality for this instance are not  set !!!!");
	}


	define("POS_MULTI_SUCURSAL", 		$rs['multi_sucursal']		   === "1" );
	define("POS_COMPRA_A_CLIENTES", 	$rs['compra_a_clientes']	   === "1" );
	define("POS_MODULO_CONTABILIDAD", 	$rs['POS_MODULO_CONTABILIDAD'] === "1" );

	
	#leer las caracteristicas del sistema
	$sql = "SELECT * FROM personalization WHERE ( instance_id = ? ) ;";
	$params = array(  $_SESSION["INSTANCE_ID"] );

	$rs = $core_conn->GetRow($sql, $params);


	

	if(count($rs)==0){
		//no hay datos de personalizacion
		define("POS_STYLE_CLIENTES_BANNER", 		"../media/banners/clientes.jpeg" );
		define("POS_STYLE_SUCURSALES_BANNER", 		"../media/banners/clientes.jpeg" );
		define("POS_STYLE_VENTAS_BANNER", 			"../media/banners/2474716389_b6433e764f_b.jpg" );
		define("POS_STYLE_AUTORIZACIONES_BANNER", 	"../media/banners/clientes.jpeg" );
		define("POS_STYLE_CONTABILIDAD_BANNER", 	"../media/banners/clientes.jpeg" );
		define("POS_STYLE_PROVEEDORES_BANNER", 		"../media/banners/2101237066_7eabf6b3c8_b.jpg" );
		define("POS_STYLE_INVENTARIO_BANNER", 		"../media/banners/2474716389_b6433e764f_b.jpg" );
		
	}else{
		//si hay datos !
		define("POS_STYLE_CLIENTES_BANNER", 		$rs['mod_clientes_banner'] );
		define("POS_STYLE_SUCURSALES_BANNER", 		$rs['mod_sucursales_banner'] );
		define("POS_STYLE_VENTAS_BANNER", 			$rs['mod_ventas_banner'] );
		define("POS_STYLE_AUTORIZACIONES_BANNER", 	$rs['mod_autorizaciones_banner'] );
		define("POS_STYLE_CONTABILIDAD_BANNER", 	$rs['mod_contabilidad_banner'] );
		define("POS_STYLE_PROVEEDORES_BANNER", 		$rs['mod_proveedores_banner'] );
		define("POS_STYLE_INVENTARIO_BANNER", 		$rs['mod_inventario_banner'] );
										
	}
	
	
	require('db/DBConnection.php');
	
