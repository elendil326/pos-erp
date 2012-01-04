<?php
/**
  * POST api/precio/regla/eliminar
  * Elimina una regla
  *
  * Elimina una regla. La regla por default de l aversion por default de la tarifa por default no puede ser eliminada
  *
  *
  *
  **/

  class ApiPrecioReglaEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_regla" => new ApiExposedProperty("id_regla", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EliminarRegla( 
 			
			
			isset($_POST['id_regla'] ) ? $_POST['id_regla'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
