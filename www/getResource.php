<?php

define("I_AM_GET_RESOURCE", true);

if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

//requerir la configuracion
require ( "../server/bootstrap.php" );


if(is_file("controller/login.controller.php"))
	require_once('controller/login.controller.php');
else
	require_once('../server/controller/login.controller.php');

function writeConfig(){

	

}

//cargar cada directorio
function loadDir( $dir, $type )
{

	//leeer los archivos en ese directorio
	$address = '../'.$type.'/'.$dir.'/';
	
	if ($handle = opendir($address)) {
		
	    while ($file = readdir($handle)) {
			
			if( endsWith( $file, "." . $type ) ){
				echo file_get_contents($address . $file) . "\n";

			}
	    }//directory loop

	    closedir($handle);
	}
}



//revisar parametros
if(! ( isset($_REQUEST['mod']) && isset($_REQUEST['type'] ) )) {
	Logger::log("    ->getResource.php: Solicitud de recurso incorrecto.");
	die('{"success":false}');
}


$module = $_REQUEST['mod'];
$type = $_REQUEST['type'];


//imprimir el header
switch($type)
{
	case 'js' : header('Content-Type:text/javascript'); break;
	case 'css' : header('Content-Type:text/css'); break;
	default : 
		Logger::log("    ->getResource.php: Solicitud de recurso con un tipo invalido:" . $type);
		die('{"success":false}');
}




switch($module)
{
	
	
	/**
	 * Cargar modulos de Administrador
	 * 
	 * */
	case 'admin' :
		if(defined("I_AM_JEDI") && I_AM_JEDI){
			loadDir( $module, $type );
			break;
		}
		
		if(isset($_SESSION['grupo']) && 
			($_SESSION['grupo'] == 1 || $_SESSION['grupo'] == 0 || $_SESSION['grupo']  == "JEDI"))
			loadDir( $module, $type );
		else{
			Logger::log("    ->getResource.php: Solicitud de recurso para admin/ingenieria sin sesion valida.");
			die("/* ACCESO DENEGADO */");
		}
		
	break;
	
	/**
	 * Cargar modulos de Sucursal
	 * 
	 * */
	case 'sucursal':
	
		if(!sucursalTest()){
			logOut(false);
			die("window.location = \".\";");
		}
		
		if(!isset($_SESSION['grupo'])){
			
			Logger::log("    ->getResource.php: Solicitud de recurso para sucursal sin sesion valida.");
			Logger::log("    ->getResource.php: La variable de grupo no existe !");
						
            if( $type == "js" ){
				Logger::log("    ->getResource.php: Ya que me solicitaron un JS enviare una redireccion al homepage.");				
    			die("window.location = \".\";");
            }
			
			die("/* ACCESO DENEGADO */");
		}

		
		if($type == "css"){
			loadDir($module, $type);
			break;
		}
		
		echo "Ext.ns('POS', 'sink', 'Ext.ux');";			
		
		//escribir la configuracion
		writeConfig();
		
		//cargar modulos de sucursal
		loadDir( "sucursal/pre" , $type );


        //imprimir que tipo de usuario soy
        if(isset($_SESSION['grupo'])){
	        	echo "POS.U.g = " . (($_SESSION['grupo'] == 2) ? "true" : "false" ) . ";";	
			//echo "POS.CHECK_DB_TIMEOUT = ".HEARTBEAT_INTERVAL.";";
		}

			


		loadDir( "sucursal/apps" , $type );

					
		if($_SESSION['grupo'] == 2 ){
			//si es gerente tambien cargar los de gerencia
			loadDir( "sucursal/apps/gerente" , $type );
		}

		loadDir( "sucursal/apps/post" , $type );
		
		loadDir( "sucursal/post" , $type );

	break;
	
	
	//cargar modulos compartidos
	case 'shared': loadDir( $module, $type ); break;
	
	//cargar login
	case 'login' : loadDir( $module, $type ); break;
	
	//cargar login
	case 'cliente' : loadDir( $module, $type ); break;
	
	
	default : 
		Logger::log("    ->getResource.php: Solicitud de recurso de modulo inexistente: " . $module);
		die('{"success":false}');
	
}
	
