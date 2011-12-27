<?php
/**
  * GET api/paquete/eliminar
  * Elimina un paquete
  *
  * Desactiva un paquete.
  *
  *
  *
  **/

  class ApiPaqueteEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_paquete" => new ApiExposedProperty("id_paquete", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PaquetesController::Eliminar( 
 			
			
			isset($_GET['id_paquete'] ) ? $_GET['id_paquete'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
