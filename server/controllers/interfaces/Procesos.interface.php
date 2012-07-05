<?php
/**
  *
  *
  *
  **/
	
  interface IProcesos {
  
  
	/**
 	 *
 	 *Define un nuevo proceso y muchas cosas mas
 	 *
 	 * @param descripcion string descripcion del proceso
 	 * @param nombre string nombre del proceso
 	 * @return id_proceso int Identificador del proceso creado
 	 **/
  static function Nuevo
	(
		$descripcion, 
		$nombre
	);  
  
  
	
  }
