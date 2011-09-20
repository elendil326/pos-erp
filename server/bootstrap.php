<?php
	
	# *******************************
	# Buscar la ruta de /SERVER
	# *******************************
	define('POS_PATH_TO_SERVER_ROOT', str_replace("/bootstrap.php", "", __FILE__ ));
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);


	# *******************************
	# Buscar la configuracion y cargarla
	# *******************************
    require_once("config.default.php");
	
	if(is_file("config.php"))
	{
		//hay una configuracion especifica, load it
		include("config.php");
	}



	# *******************************
	# Convertir la configuracion en definiciones
	# *******************************

	define("POS_CONFIG_LOG_TO_FILE", 			$POS_CONFIG["LOG_TO_FILE"]);
	define("POS_CONFIG_LOG_ACCESS_FILE", 		$POS_CONFIG["LOG_ACCESS_FILE"]);
	define("POS_CONFIG_LOG_ERROR_FILE", 		$POS_CONFIG["LOG_ERROR_FILE"]);
	define("POS_CONFIG_LOG_TRACKBACK", 			$POS_CONFIG["LOG_TRACKBACK"]);
	define("POS_CONFIG_LOG_DB_QUERYS", 			$POS_CONFIG["LOG_DB_QUERYS"]);
	define("POS_CONFIG_ZONA_HORARIA", 			$POS_CONFIG["ZONA_HORARIA"]);
	define("POS_CONFIG_CORE_DB_USER", 			$POS_CONFIG["CORE_DB_USER"]);
	define("POS_CONFIG_CORE_DB_PASSWORD", 		$POS_CONFIG["CORE_DB_PASSWORD"]);
	define("POS_CONFIG_CORE_DB_NAME", 			$POS_CONFIG["CORE_DB_NAME"]);
	define("POS_CONFIG_CORE_DB_DRIVER", 		$POS_CONFIG["CORE_DB_DRIVER"]);
	define("POS_CONFIG_CORE_DB_HOST", 			$POS_CONFIG["CORE_DB_HOST"]);
	define("POS_CONFIG_CORE_DB_DEBUG", 			$POS_CONFIG["CORE_DB_DEBUG"]);





	# *******************************
	# Requereir lo indispensable para seguir
	# *******************************
	require_once("libs/Logger.php");
	require_once('libs/adodb5/adodb.inc.php');
	require_once('libs/adodb5/adodb-exceptions.inc.php');


	# *******************************
	# Conectarme a la base de datos CORE
	# *******************************
	$POS_CONFIG["CORE_CONN"] = null;

	try{

	    $POS_CONFIG["CORE_CONN"] = ADONewConnection(POS_CONFIG_CORE_DB_DRIVER);
	    $POS_CONFIG["CORE_CONN"]->debug = POS_CONFIG_CORE_DB_DEBUG;
	    $POS_CONFIG["CORE_CONN"]->PConnect(POS_CONFIG_CORE_DB_HOST, POS_CONFIG_CORE_DB_USER, POS_CONFIG_CORE_DB_PASSWORD, POS_CONFIG_CORE_DB_NAME);

	    if(!$POS_CONFIG["CORE_CONN"])
	    {

	    	Logger::error( "Imposible conectarme a la base de datos." );
			die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));    
	    }

	} catch (ADODB_Exception $ado_e) {
	
		Logger::error( $ado_e );
		die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
		
		

	}catch ( Exception $e ){
		Logger::error( $e );
		die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
	}



	# *******************************
	# Buscar esta instancia si es que la necesito
	# *******************************
	//esta definicion se hace si NO queremos
	//que sea validada la instancia
	if(defined("BYPASS_INSTANCE_CHECK"))
	{
		

		//saltar el checkeo siguiente
		return;
	}





	# *******************************
	# Cosas de la instancia
	# *******************************
