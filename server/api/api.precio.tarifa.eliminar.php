<?php
/**
  * POST api/precio/tarifa/eliminar
  * Desactiva una tarifa
  *
  * Desactiva una tarifa. Para poder desactivar una tarifa, esta no tiene que estar asignada como default para ningun usuario. La tarifa default del sistema no puede ser eliminada.

La tarifa instalada por default no puede ser eliminada
  *
  *
  *
  **/

  class ApiPrecioTarifaEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EliminarTarifa( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
