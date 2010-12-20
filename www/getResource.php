<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

//requerir la configuracion
require ( "../server/config.php" );

session_start();


/* 
 * Este archivo es para el JSloader, recibe un parametro que es la carpeta dentro de js,
 * y regresa todos los archivos dentro de esa carpeta para que javascript los cargue 
 */


	//incluir jsmin
	if(_POS_JSMINIFY){
		require_once("misc/JSMin.php");		
	}


	//minificar js
	function miniJS( $fileData ){
		$data = "";
		
		foreach( $fileData as $line  ){
			$data .= $line;
		}

		$salida = JSMin::minify($data);
		
		echo $salida;
	}



	//minificar css
	function miniCSS( $fileData ){
		$data = "";
		
		foreach( $fileData as $line  ){
			$data .= $line;
		}
		
		//$salida = JSMin::minify($data);
		$salida = $data;
		
		echo $salida;
	}


	//cargar cada directorio
	function loadDir( $dir, $type )
	{


		//leeer los archivos en ese directorio
		$address = '../'.$type.'/'.$dir.'/';
		
		if ($handle = opendir($address)) {
			
		    while ($file = readdir($handle)) {
				
				if( endsWith( $file, "." . $type ) ){
					$lines = file($address . $file);
					if(_POS_JSMINIFY) {
						
						if($type=="js") miniJS ($lines);
						
						if($type=="css") miniCSS ($lines);

					}else{
						foreach($lines as $line) echo  $line;
					}
					echo "\n";
				}
				
				
				
		    }//directory loop

		    closedir($handle);
		}
		
		
		//y al final del archivo llamar al javascript que dice que ha terminado, solo si es javascript
		if($type=="js"){
            //imprimir que tipo de usuario soy
            if(isset($_SESSION['grupo']))
                echo "POS.U.g = " . (($_SESSION['grupo'] == 2) ? "true" : "false" ) . ";";

			?> if(window.JSLoader !== undefined) {	JSLoader.callback(); }	<?php			
		}


	}


	//revisar parametros
	if(! ( isset($_REQUEST['mod']) && isset($_REQUEST['type'] ) )) die("{success: false}");
	
	$module = $_REQUEST['mod'];
	$type = $_REQUEST['type'];

	
	
	//imprimir el header
	switch($type)
	{
		case 'js' : header('Content-Type:text/javascript'); break;
		case 'css' : header('Content-Type:text/css'); break;
		default : die("{success: false}");
		
	}
	
	
	
	
	switch($module)
	{
		//cargar modulos de admin
		case 'admin' :
			
			if(isset($_SESSION['grupo']) && $_SESSION['grupo'] == 1)
				loadDir( $module, $type );
			else
				die("/* ACCESO DENEGADO */");
		break;
		
		//cargar modulos de sucursal
		case 'sucursal':
		
			if(!isset($_SESSION['grupo']))
				die(" /* ACCESO DENEGADO */ window.location = '.'; /* ACCESSO DENEGADO */ ");
			
			if($type == "css"){
				loadDir($module, $type);
				break;
			}
			
			//cargar modulos de sucursal
			loadDir( "sucursal/pre" , $type );

			loadDir( "sucursal/apps" , $type );
						
			if($_SESSION['grupo'] == 2 ){
				//si es gerente tambien cargar los de gerencia
				loadDir( "sucursal/apps/gerente" , $type );
			}
			
			loadDir( "sucursal/post" , $type );

		break;
		
		
		//cargar modulos compartidos
		case 'shared': loadDir( $module, $type ); break;
		
		//cargar login
		case 'login' : loadDir( $module, $type ); break;
		
		default : die("{success: false}");
		
	}
	

?>
