<?php
/**
  *
  *
  *
  **/
	
  interface IFormasPreimpresas {
  
  
	/**
 	 *
 	 *Crea un PDF Gen?rico a partir de un JSON
 	 *
 	 * @param formato json Formato que describe el formato del documento
 	 * @return status string ok
 	 **/
  static function GenericoPdf
	(
		$formato
	);  
  
  
	
  }
