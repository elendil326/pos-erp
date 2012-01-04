<?php
/**
  * POST api/precio/version/activar
  * Activa una version
  *
  * Activa una version. Como solo puede haber una version activa por tarifa, este metodo desactiva la version actualmente activa de la tarifa y activa la version obtenida como parametro.
  *
  *
  *
  **/

  class ApiPrecioVersionActivar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::ActivarVersion( 
 			
			
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
