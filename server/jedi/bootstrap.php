<?php

	# *******************************
	# Bootstrap para JEDI !
	# *******************************

	//requerir la configuracion
	define('POS_PATH_TO_SERVER_ROOT', dirname(__DIR__)); 
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);
    require_once('../../server/config.php');

	

	session_name("POS_ID");

	session_set_cookie_params ( 0  , '/' );

	require_once("logger.php");

	require_once("jedi/static.php");

	require_once("admin/includes/static.php");

	$ss = session_start (  );

	if(!$ss){
		echo '{"success": false,"reason": "Imposible iniciar sesion." }';
		return;
	}

	require_once('utils.php');

	//conectarme a la base de datos pos_core
	require('db/PosCoreDBConnection.php');
	
	if(defined("I_AM_LOGIN") && I_AM_LOGIN ){
		//soy la pagina de login, dejame pasar solo por ensimita :P
		Logger::log("Bypassing login validation, since i am the login page !");
		
		//epic return neeeded here 
		return;
	}
	
	//probar la sesion actual
	if(! JediLogin::isValidSession() ){
		die(header("Location: login.php "));
	}
		
	//todo bien !
	//go on with your life

	
	
