<?php
/**
  * GET api/sesion/iniciar
  * Validar e iniciar una sesion.
  *
  * Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. Este m?todo no necesita de ning?n tipo de autenticaci?n. 
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar? un 403 Authorization Required y la sesi?n no se iniciar?.
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required supongo
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
			"password" => new ApiExposedProperty("password", true, GET, array( "string" )),
			"usuario" => new ApiExposedProperty("usuario", true, GET, array( "string" )),
			"request_token" => new ApiExposedProperty("request_token", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SesionController::Iniciar( 
 			
			
			isset($_GET['password'] ) ? $_GET['password'] : null,
			isset($_GET['usuario'] ) ? $_GET['usuario'] : null,
			isset($_GET['request_token'] ) ? $_GET['request_token'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
