<?php
/**
  * GET api/precio/tarifa/activar
  * Activa una tarifa previamente eliminada
  *
  * Activa una tarifa previamente eliminada.
  *
  *
  *
  **/

  class ApiPrecioTarifaActivar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::ActivarTarifa( 
 			
			
			isset($_GET['id_tarifa'] ) ? $_GET['id_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
