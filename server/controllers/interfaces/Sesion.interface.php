<?php
/**
  *
  *
  *
  **/
	
  interface ISesion {
  
  
	/**
 	 *
 	 *Regresa un url de redireccion seg?n el tipo de usuario.
 	 *
 	 * @param auth_token string El token de autorizacion generado al iniciar la sesion
 	 * @return forward_to string La url de continuación de acuerdo al id que cerró sesión.
 	 **/
  static function Cerrar
	(
		$auth_token = null
	);  
  
  
	
  
	/**
 	 *
 	 *Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. Este m?todo no necesita de ning?n tipo de autenticaci?n. 
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar? un 403 Authorization Required y la sesi?n no se iniciar?.
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required supongo
 	 *
 	 * @param password string La contraseña del usuario.
 	 * @param usuario string El id de usuario a intentar iniciar sesión.
 	 * @param request_token bool Si se envía, y es verdadero, el seguimiento de esta sesión se hará mediante un token, de lo contrario se hará mediante cookies.
 	 * @return usuario_grupo int El grupo al que este usuario pertenece.
 	 * @return siguiente_url string La url a donde se debe de redirigir.
 	 * @return login_succesful	 bool Si la validación del usuario es correcta.
 	 * @return auth_token string El token si es que fue solicitado.
 	 **/
  static function Iniciar
	(
		$password, 
		$usuario, 
		$request_token = null
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener las sesiones activas.
 	 *
 	 * @param id_grupo int Obtener la lista de sesiones activas para un grupo de usuarios especifico.
 	 * @return en_linea json Arreglo de objetos que contendrán la información de las sesiones activas
 	 **/
  static function Lista
	(
		$id_grupo = null
	);  
  
  
	
  }
