<?php

	/*** UTILITY FUNCTION ***/
	function object_to_array($mixed) {
	    if(is_object($mixed)) $mixed = (array) $mixed;
	    if(is_array($mixed)) {
	        $new = array();
	        foreach($mixed as $key => $val) {
	            $key = preg_replace("/^\\0(.*)\\0/","",$key);
	            $new[$key] = object_to_array($val);
	        }
	    } 
	    else $new = $mixed;
	    return $new;        
	}
	/*** UTILITY FUNCTION ***/



	# *******************************
	# Revision de SubVersion
	# *******************************
	exec("svn info", $r_exec);
	for ($i=0; $i < sizeof($r_exec); $i++) { 
		if(substr ( $r_exec[$i], 0, 9 ) == "Revision:"){
			define( "SVN_REV" ,  substr ( $r_exec[$i], 10 ));
		}
	}

	
	# *******************************
	# Buscar la ruta de /SERVER
	# *******************************
	define('POS_PATH_TO_SERVER_ROOT', str_replace("bootstrap.php", "", __FILE__ ));
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);


	date_default_timezone_set ( "America/Mexico_City" );


	

	# *******************************
	# Buscar la configuracion y cargarla
	# *******************************
	require_once("config.default.php");
	require_once("Exceptions.php");
	require_once("libs/Logger.php");
	
	if(is_file(__DIR__ . "/config.php"))
	{
		//hay una configuracion especifica, load it
		include(__DIR__ . "/config.php");
		
	}







	# *******************************
	# 
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





	if(is_file("config.php"))
	{
		include 'config.php';
		//Logger::warn("config.php no exitste. Usando config.defatult.php");
	}


	# *******************************
	# Requereir lo indispensable para seguir
	# *******************************
	require_once('libs/adodb5/adodb.inc.php');
	require_once('libs/adodb5/adodb-exceptions.inc.php');


	require_once("libs/predis/lib/Predis/Autoloader.php");


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
	# Cargar las librerias de GUI
	# *******************************
	require_once("libs/gui/Page.php");
	require_once("libs/gui/StdPage.php");
	require_once("libs/gui/StdComponentPage.php");

	require_once("libs/gui/GuiComponent.php");
	require_once("libs/gui/LoginComponent.php");
	require_once("libs/gui/FormComponent.php");
	require_once("libs/gui/DAOFormComponent.php");
	require_once("libs/gui/MessageComponent.php");
	require_once("libs/gui/MenuComponent.php");
	require_once("libs/gui/TitleComponent.php");
	require_once("libs/gui/TableComponent.php");
	require_once("libs/gui/PosComponentPage.php");		
	require_once("libs/gui/GerenciaComponentPage.php");
	require_once("libs/gui/GerenciaTabPage.php");
	require_once("libs/gui/JediComponentPage.php");
	require_once("libs/gui/FreeHtmlComponent.php");
	require_once("libs/gui/ShoppingCartComponent.php");	
	require_once("libs/gui/SearchProductComponent.php");		
	require_once("libs/gui/SucursalSelectorComponent.php");
	require_once("libs/gui/EmpresaSelectorComponent.php");
	require_once("libs/gui/ReporteComponent.php");	
	require_once("libs/gui/BuyingCartComponent.php");		
	
	require_once("libs/gui/BannerComponent.php");		
	require_once("libs/gui/ClienteSelectorComponent.php");	
	



	/*require_once("controllers/login.controller.php");*/
	//require_once("controllers/instances.controller.php");
    
	require_once("libs/SessionManager.php");
    require_once("libs/FormatTime.php");
    require_once("libs/CNumeroaLetra.php");


	# *******************************
	# Cargar los DAO
	# *******************************
	require_once("model/model.inc.php");



	# *******************************
	# Cargar los Controllers
	# *******************************
    require_once("controllers/Validaciones.controllers.php");
    require_once("controllers/Instancias.controller.php");
	require_once("controllers/Clientes.controller.php");
	require_once("controllers/Sesion.controller.php");
	require_once("controllers/PersonalYAgentes.controller.php");

	require_once("controllers/Empresas.controller.php");
	require_once("controllers/Direcciones.controller.php");	
	require_once("controllers/Sucursales.controller.php");		
	require_once("controllers/Productos.controller.php");
	require_once("controllers/Tarifas.controller.php");
	require_once("controllers/Inventario.controller.php");
	require_once("controllers/Ventas.controller.php");
	require_once("controllers/Almacenes.controller.php");
	require_once("controllers/Servicios.controller.php");
	require_once("controllers/CargosYAbonos.controller.php");
	require_once("controllers/Efectivo.controller.php");
	require_once("controllers/POS.controller.php");
	require_once("controllers/Impresiones.controller.php");	
	require_once("controllers/Cajas.controller.php");
	require_once("controllers/Ayuda.controller.php");	
	require_once("controllers/Proveedores.controller.php");
	require_once("controllers/Compras.controller.php");

		
		
			
	/*
	require_once("controllers/Autorizaciones.controller.php");
	require_once("controllers/Cheques.controller.php");
	require_once("controllers/Consignaciones.controller.php");
	require_once("controllers/Contabilidad.controller.php");
	
	require_once("controllers/Documentos.controller.php");
	require_once("controllers/Impuestos.controller.php");
	require_once("controllers/Paquetes.controller.php");
	require_once("controllers/Reportes.controller.php");
	require_once("controllers/TransportacionYFletes.controller.php");
	*/

	require_once("libs/api/ApiHandler.php");




	//convertir configuracion en globales para
	//que no se pueda modificar
	define("MAIL", 				$POS_CONFIG["MAIL"]);
	define("MAIL_FROM", 	$POS_CONFIG["MAIL_FROM"]);
	define("MAIL_HOST", 	$POS_CONFIG["MAIL_HOST"]);
	define("MAIL_PORT", 	$POS_CONFIG["MAIL_PORT"]);
	define("MAIL_USERNAME", $POS_CONFIG["MAIL_USERNAME"]);
	define("MAIL_PASSWORD", $POS_CONFIG["MAIL_PASSWORD"]);
	
	define("GOOGLE_ANALYTICS_ID", $POS_CONFIG["GOOGLE_ANALYTICS_ID"]);

	
	# *******************************
	define('POST', "__ISPOST__");
	define('GET', "__ISGET__");
	define('BYPASS', '-1');
	# *******************************
	



	if(class_exists("PHPUnit_Runner_Version")){
		$_GET["_instance_"] = $POS_CONFIG["PHPUNIT_INSTANCE_TO_TEST"];		
		define("BYPASS_INSTANCE_CHECK", false);
	}




	# *******************************
	# Buscar esta instancia si es que la necesito
	# *******************************
	//esta definicion se hace si NO queremos
	//que sea validada la instancia
	if(defined("BYPASS_INSTANCE_CHECK") && BYPASS_INSTANCE_CHECK)
	{
		//saltar el checkeo siguiente
		return;
	}



	


	# *******************************
	# Cosas de la instancia
	# *******************************
	if(!isset($_GET["_instance_"]))
	{
		Logger::error("No hay instancia en el url !");
		die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
	}
	


		
	$sql = "SELECT * FROM instances WHERE ( instance_token = ? ) LIMIT 1;";

	$params = array( $_GET["_instance_"] );

	$rs = $POS_CONFIG["CORE_CONN"]->GetRow($sql, $params);

	if(count($rs) === 0)
	{
		Logger::warn("La instancia para el token {". $_GET["_instance_"] ."} no exite !");
		die(header("HTTP/1.1 404 NOT FOUND"));
	}


	
	# *******************************
	# Base de datos de la instancia
	# *******************************
	
	$POS_CONFIG["INSTANCE_CONN"] = null;
	define("IID", $rs["instance_id"]);
	try{

	    $POS_CONFIG["INSTANCE_CONN"] = ADONewConnection($rs["db_driver"]);
	    $POS_CONFIG["INSTANCE_CONN"]->debug = $rs["db_debug"];
	    $POS_CONFIG["INSTANCE_CONN"]->PConnect($rs["db_host"], $rs["db_user"], $rs["db_password"], $rs["db_name"]);

	    if(!$POS_CONFIG["INSTANCE_CONN"])
	    {

	    	Logger::error( "Imposible conectarme a la base de datos de la instancia." );
			die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));    
	    }

	} catch (ADODB_Exception $ado_e) {
	
		Logger::error( $ado_e );
		die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
		
		

	}catch ( Exception $e ){
		Logger::error( $e );
		die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
	}

	if($POS_CONFIG["INSTANCE_CONN"] === NULL){
		Logger::error("Imposible conectarse con la base de datos de la instancia...");
		die(header("HTTP/1.1 500 INTERNAL SERVER ERROR"));
	}
	
	$conn = $POS_CONFIG["INSTANCE_CONN"];
	


	/**
	 * 
	 * Actualizar el token de este usuario
	 * 
	 * 
	 * */
	SesionController::Limpiar(); 
	SesionController::HeartBeat();	

	//DAO::predis("db".$rs["instance_id"], "127.0.0.1");
	