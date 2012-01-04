<?php
/**
  * POST api/precio/version/setDefault
  * Pone como default a la version obtenida para esta tarifa
  *
  * Pone como default a la version obtenida para esta tarifa. Solo puede haber una version default por tarifa, asi que este metodo le quita el default a la version que lo era anteriormente y lo pone en la version obtenida como parametro.

Una version default no puede caducar.
  *
  *
  *
  **/

  class ApiPrecioVersionSetDefault extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::SetDefaultVersion( 
 			
			
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
