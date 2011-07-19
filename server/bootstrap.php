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
	//nombre de la galleta
	//session_set_cookie_params ( int $lifetime [, string $path [, string $domain [, bool $secure = false [, bool $httponly = false ]]]] )
	session_name("POS_ID");

	session_set_cookie_params ( 0  , '/' );

	//logger
	require_once("logger.php");


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

	//tengo el id de instancia ?
	if(!isset($_GET["i"]) && !isset($_SESSION["INSTANCE_ID"]) ){

		//la pagina de login, pone I_AM_LOGIN en verdadero, 
		//es una buena manera de saber si vengo del login
		//si vengo del login
		if(defined("I_AM_LOGIN") && I_AM_LOGIN) {
			Logger::log("I_AM_LOGIN: There is no instance number nowhere !!" );
			die("NO INSTANCE !");
			
		}
		
		
		if(defined("I_AM_SUCURSAL") && I_AM_SUCURSAL) {
			Logger::log("I_AM_SUCURSAL: There is no instance number nowhere !!" );				
			die("NO INSTANCE !");
				
		}

		if(defined("I_AM_CLIENTE") && I_AM_CLIENTE) {
			Logger::log("I_AM_CLIENTE: There is no instance number nowhere !!" );				
			die('<div align=center><img src="media/intro.png"></div>');
		}
		
		if(defined("I_AM_PROXY") && I_AM_PROXY) {
			Logger::log("I_AM_PROXY: There is no instance number nowhere !!" );				
			die( '{"success": false , "reason": "Accesso denegado" , "reboot" : true }');
		}

		Logger::log("SOURCE : " . $_SERVER['REQUEST_URI']);
		Logger::log("     There is no instance number nowhere, sending header to log.php !!" );
		die(header("Location: ./log.php"));
		
	}
	
	
	
	//no esta la de sescion, pero esta el get
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
		Logger::log("This instance does not exist !!!",3);
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

	define("HEARTBEAT_METHOD_TRIGGER", 		$rs['HEARTBEAT_METHOD_TRIGGER'] == "true");
	define("HEARTBEAT_INTERVAL", 			$rs['HEARTBEAT_INTERVAL']);

	
	# metodo de validacion de sucursales mediante user-agent
	# puede ser 'FULL_UA' o bien 'SID_TOKEN'
	# 'FULL_UA' validara 
	# 'SID_TOKEN' buscara la subcadena SID={00000} dentro del UA y
	# comparara esta cadena de 5 caracteres contra un equipo en la 
	# base de datos.
	define( 'POS_SUCURSAL_TEST_TOKEN', 		$rs['POS_SUCURSAL_TEST_TOKEN'] );
	define( 'DEMO', 						$rs['DEMO']);

	#leer las caracteristicas del sistema
	$sql = "SELECT * FROM core_functionality WHERE ( instance_id = ? ) ;";
	$params = array(  $_SESSION["INSTANCE_ID"] );

	$rs = $core_conn->GetRow($sql, $params);

	if(count($rs)==0){
		//no existe esta instancia !!
		Logger::log("This instance does not exist !!!",3);
		die("this instance does not exist !");
	}


	define("POS_MULTI_SUCURSAL", 		$rs['multi_sucursal']			);
	define("POS_COMPRA_A_CLIENTES", 	$rs['compra_a_clientes']		);
	define("POS_MODULO_CONTABILIDAD", 	$rs['POS_MODULO_CONTABILIDAD']	);

	
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
	
