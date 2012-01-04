<?php
/**
  * POST api/precio/version/eliminar
  * Elimina una version 
  *
  * Elimina una version permamentemente de la base de datos junto a todas sus reglas.

La version default de la tarifa no puede ser eliminada ni la version activa.

La version por default de la tarifa instalada por default no puede ser eliminada
  *
  *
  *
  **/

  class ApiPrecioVersionEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EliminarVersion( 
 			
			
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
