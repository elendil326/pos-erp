<?php
/**
  *
  *
  *
  **/

  interaface ISesion {
  
  
	/**
 	 *
 	 *Regresa un url de redireccion según el tipo de usuario.
 	 *
 	 **/
	protected function Cerrar();  
  
  
  
  
	/**
 	 *
 	 *Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. Este método no necesita de ningún tipo de autenticación. 
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresará un 403 Authorization Required y la sesión no se iniciará.
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required supongo
 	 *
 	 **/
	protected function Iniciar();  
  
  
  
  
	/**
 	 *
 	 *Obtener las sesiones activas.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  }
