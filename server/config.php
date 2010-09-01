<?php
/*


	Configuracion del sistema 
	
	
	*/
	
	

	
	/* BASE DE DATOS 
	
	*/
	
	

	/**
	* Usuario de la base de datos.
	*/
	define('DB_USER', 'pos');

	/**
	* Contraseña de la base de datos.
	*/
	define('DB_PASSWORD', 'pos');

	/**
	* Nombre de la base de datos.
	*/
	define('DB_NAME', 'pos');

	/**
	* Nombre del driver para conectar al DBMS.
	*/
	define('DB_DRIVER', 'mysql');

	/**
	* Donde se encuentra el DBMS.
	*
	* IP | domain | localhost
	*/
	define('DB_HOST', 'localhost');


	/**
	* Habilitar o no el debug de ADOdb.
	*
	* true habilita debug. false deshabilita debug. 
	*
	* Default false.
	*/
	define('DB_DEBUG', false);
	
	
	
	
	//serguridad
	//timeout's
	//cada que una sesion sobrepase de este valor, volvera a pedir las credenciales
	$__ADMIN_TIME_OUT 	= 3600;
	$__GERENTE_TIME_OUT = 3600;
	$__CAJERO_TIME_OUT 	= 3600;
	
	//calculos
	//funcion para calular el total
	//subtotal - pesos
	//iva - porcentaje
	//descuento - porcentaje
	function __pos__calcularTotal($subtotal, $iva, $descuento)
	{
		$iva /= 100;
		$descuento /= 100;
		//descuento sobre iva

		return ( ($subtotal- ($subtotal*$descuento)) + (($subtotal-($subtotal*$descuento))*$iva) );

	}
	
	
