<?php
/**
  *
  *
  *
  **/
	
  interface ISesion {
  
  
	/**
 	 *
 	 *Regresa informacion sobre la sesion actual.
 	 *
 	 * @return id_caja int 
 	 * @return id_sucursal int El id_sucursal de la sucursal donde este usuario inico sesion en caso de haberlo hecho desde un mostraodr. Un gerente no tendra id_sucursal asociada a el dado que puede iniciar sesion desde cualquier lugar.
 	 * @return id_usuario int 
 	 **/
  static function Actual
	(
	);  
  
  
	
  
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
 	 *Valida las credenciales de un usuario. Este m?todo no necesita de ning?n tipo de autenticaci?n. 
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar? un 403 Authorization Required y la sesi?n no se iniciar?.
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required.

Si request_token se envia verdadero no se asociara una cookie a esta peticion, sino que se regresara un token que debera ser enviado en cada llamada subsecuente de este cliente. Los tokens expiraran segun la configuracion del sistema.
 	 *
 	 * @param password string La contrasea del usuario en texto plano. 
 	 * @param usuario string Este puede ser el id del usuario a iniciar sesion o bien su correo electronico.
 	 * @param request_token bool Si se enva, y es verdadero, el seguimiento de esta sesin se har mediante un token, de lo contrario se har mediante cookies.
 	 * @return siguiente_url string La url a donde se debe de redirigir.
 	 * @return usuario_grupo int El grupo al que este usuario pertenece.
 	 * @return login_succesful	 bool Si la validación del usuario es correcta.
 	 * @return auth_token string El token si es que fue solicitado.
 	 **/
  static function Iniciar
	(
		$password, 
		$usuario, 
		$request_token = "true"
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
