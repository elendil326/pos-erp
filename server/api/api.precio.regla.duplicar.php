<?php
/**
  * POST api/precio/regla/duplicar
  * Duplica una regla y la guarda en otra version
  *
  * Duplica una regla y la guarda en otra version. Las reglas duplicadas actualizan el id de la version a la que pertenecen y mantienen todos sus datos.
  *
  *
  *
  **/

  class ApiPrecioReglaDuplicar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_regla" => new ApiExposedProperty("id_regla", true, POST, array( "int" )),
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::DuplicarRegla( 
 			
			
			isset($_POST['id_regla'] ) ? $_POST['id_regla'] : null,
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
