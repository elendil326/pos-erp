<?php
/*


	Configuracion del sistema 
	
	
	*/
	
	
	
	/* BASE DE DATOS 
	
	*/
	
	

	/**
	* Usuario de la base de datos.
	*/
	define('DB_USER', 'root');

	/**
	* Contraseña de la base de datos.
	*/
	define('DB_PASSWORD', '');

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
