<?php
/**
  * POST api/precio/tarifa/eliminar
  * Desactiva una tarifa
  *
  * Desactiva una tarifa. una tarifa no puede ser eliminada si es la default del sistema o si esta como default para algun usuario,rol,clasificacion de cliente o proveedor.
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
  
  
  
  
  
  
