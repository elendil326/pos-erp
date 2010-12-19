<?php
	/* *******************************
		Configuracion Basica
	 ********************************* */
	
	
	/*
	To add a path to the already existing value (not just replace) do something like this:
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/path/to/add')
	*/

	//carpeta donde se encuentran los scripts del servidor
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/Applications/XAMPP/xamppfiles/htdocs/svn/pos/trunk/server");
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/var/www/caffeina/pos/trunk/server");
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . "/var/www/pos/trunk/server");

	//mimificar o no el javascript que se carga
	define("_POS_JSMINIFY", false);
	
	//titulo de la aplicacion
	define("_POS_HTMLTITLE", "Papas Supremas");




	
    /* *******************************
		LOG
	 ********************************* */
	define("_POS_LOG_TO_FILE", true);
	define("_POS_LOG_TO_FILE_FILENAME", "/var/log/mx.caffeina.pos/pos.log");


	

    /* *******************************
		ZONA HORARIA
	 ********************************* */
	date_default_timezone_set("America/Mexico_City");

	
	/* *******************************
		BASE DE DATOS 
	 ********************************* */

	define('DB_USER', 		'pos');
	define('DB_PASSWORD', 	'pos');
	define('DB_NAME', 		'pos');
	define('DB_DRIVER', 	'mysql');
	define('DB_HOST', 		'localhost');
	define('DB_DEBUG', 		false);
	
	
	define('POS_MAX_LIMITE_DE_CREDITO', 20000);
	define('POS_MAX_LIMITE_DESCUENTO', 35.0);
	define('POS_ENABLE_GMAPS', false);	
    
	
	/* *******************************
		Seguridad
	 ********************************* */
	//cada que una sesion sobrepase de este valor, volvera a pedir las credenciales
	$__ADMIN_TIME_OUT 	= 3600;
	$__GERENTE_TIME_OUT = 3600;
	$__CAJERO_TIME_OUT 	= 3600;
	




	function __pos__calcularTotal($subtotal, $iva, $descuento)
	{
		//funcion para calular el total
		//subtotal - pesos
		//iva - porcentaje
		//descuento - porcentaje
		$iva /= 100;
		$descuento /= 100;
		//descuento sobre iva

		return ( ($subtotal- ($subtotal*$descuento)) + (($subtotal-($subtotal*$descuento))*$iva) );

	}



	/* *******************************
		Funciones de ayuda
	 ********************************* */
	function endsWith( $str, $sub ) {
		return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
	}
