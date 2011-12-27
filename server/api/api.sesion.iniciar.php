<?php
/**
  * POST api/sesion/iniciar
  * Validar e iniciar una sesion.
  *
  * Valida las credenciales de un usuario. Este m?do no necesita de ning?ipo de autenticaci?
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar?n 403 Authorization Required y la sesi?o se iniciar?
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required.

Si request_token se envia verdadero no se asociara una cookie a esta peticion, sino que se regresara un token que debera ser enviado en cada llamada subsecuente de este cliente. Los tokens expiraran segun la configuracion del sistema.
  *
  *
  *
  **/

  class ApiSesionIniciar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"password" => new ApiExposedProperty("password", true, POST, array( "string" )),
			"usuario" => new ApiExposedProperty("usuario", true, POST, array( "string" )),
			"request_token" => new ApiExposedProperty("request_token", false, POST, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SesionController::Iniciar( 
 			
			
			isset($_POST['password'] ) ? $_POST['password'] : null,
			isset($_POST['usuario'] ) ? $_POST['usuario'] : null,
			isset($_POST['request_token'] ) ? $_POST['request_token'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
