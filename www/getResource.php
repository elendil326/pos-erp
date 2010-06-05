<?php
session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();




/* 
 * Este archivo es para el JSloader, recibe un parametro que es la carpeta dentro de js,
 * y regresa todos los archivos dentro de esa carpeta para que javascript los cargue de .... awebo
 */


	$comprimir = true;

	//incluir jsmin
	include_once("../server/JSMin.php");

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
		GLOBAL $comprimir;		

		

		//leeer los archivos en ese directorio
		$address = '../'.$type.'/'.$dir.'/';
		
		if ($handle = opendir($address)) {
		    while (false !== ($file = readdir($handle))) {

				//si termina con js
				if( endsWith( $file, "." . $type ) )
				{
					$lines = file($address . $file);

					if($comprimir) {
						
						if($type=="js")
							miniJS ($lines);
						
						if($type=="css")
							miniCSS ($lines);						
					}else{
						
							foreach($lines as $line)
							{
								echo  $line;
							}						
						
					}
					

					echo "\n";
					
				}
				
				
				
		    }//directory loop

		    closedir($handle);
		}
		
		
		//y al final del archivo llamar al javascript que dice que ha terminado, solo si es javascript
		if($type=="js"){
			?> if(window.JSLoader !== undefined) {	JSLoader.callback(); }	<?php			
		}


	}




	//util function
	function endsWith( $str, $sub ) {
		return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
	}



	/* main */
	if(! ( isset($_REQUEST['mod']) && isset($_REQUEST['type'] ) )) die("{success: false}");
	
	$module = $_REQUEST['mod'];
	$type = $_REQUEST['type'];

	
	switch($type)
	{
		case 'js' : header('Content-Type:text/javascript'); break;
		case 'css' : header('Content-Type:text/css'); break;
		default : die("{success: false}");
		
	}
	
	switch($module)
	{
		case 'trunk' : loadDir( $module, $type ); break;
		case 'shared' : loadDir( $module, $type ); break;
		default : die("{success: false}");
		
	}
	

?>
