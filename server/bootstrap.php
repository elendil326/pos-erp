<?php
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
	$ss = session_start (  );

	if(!$ss){
		echo '{"success": false,"reason": "Imposible iniciar sesion." }';
		return;
	}



	require_once('utils.php');

	//tengo el id de instancia ?
	if(!isset($_GET["i"])
		&& !isset($_SESSION["INSTANCE_ID"])
	){
		
		Logger::log("There is no instance number nowhere !!" );
		
		//la pagina de login, poner I_AM_LOGIN en verdadero, 
		//es una buena manera de saber si vengo del login
		//si vengo del login, entonces me vale si no hay instancia
		//puedo sacarla despues de que el usaurio ponga sus credenciales
		if(defined("I_AM_LOGIN") && I_AM_LOGIN) {
			//
			//Logger::log("Showing log page, althoug there is no instance id");
			//return;
			die("NO INSTANCE !");
			
		}else{
			//no estoy en el log.php, redireccionar a el
			die(header("Location: ./log.php"));
		}

		
	}
	
	//no esta la de sescion, pero esta el get
	if(!isset($_SESSION["INSTANCE_ID"]) && isset($_GET["i"])){
		Logger::log("There is no instance in the cooke, setting that bitch" );
		$_SESSION["INSTANCE_ID"] = $_GET["i"];
	}



	//estan las dos, pero son diferentes
	if(isset($_SESSION["INSTANCE_ID"]) && isset($_GET["i"])
		&& $_SESSION["INSTANCE_ID"] != $_GET["i"]
		){
		Logger::log("SE HA CAMBIADO LA INSTANCIA !!!!",3 );
		
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

		
	require('db/DBConnection.php');
	

	
	
	

	
	
	
	
