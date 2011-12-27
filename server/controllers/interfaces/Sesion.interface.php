<?php
/**
  *
  *
  *
  **/
	
  interface ISesion {
  
  
	/**
 	 *
 	 *Regresa un url de redireccion seg?l tipo de usuario.
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
 	 *Valida las credenciales de un usuario. Este m?do no necesita de ning?ipo de autenticaci?
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar?n 403 Authorization Required y la sesi?o se iniciar?
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required.

Si request_token se envia verdadero no se asociara una cookie a esta peticion, sino que se regresara un token que debera ser enviado en cada llamada subsecuente de este cliente. Los tokens expiraran segun la configuracion del sistema.
 	 *
 	 * @param password string La contrasea del usuario en texto plano. 
 	 * @param usuario string Este puede ser el id del usuario a iniciar sesion o bien su correo electronico.
 	 * @param request_token bool Si se enva, y es verdadero, el seguimiento de esta sesin se har mediante un token, de lo contrario se har mediante cookies.
 	 * @return auth_token string El token si es que fue solicitado.
 	 * @return login_succesful	 bool Si la validación del usuario es correcta.
 	 * @return usuario_grupo int El grupo al que este usuario pertenece.
 	 * @return siguiente_url string La url a donde se debe de redirigir.
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
