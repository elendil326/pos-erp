<?php
/**
  * GET api/precio/version/lista
  * Lista las versiones existentes
  *
  * Lista las versiones existentes, se puede filtrar por la tarifa y ordenar por los atributos de al tabla
  *
  *
  *
  **/

  class ApiPrecioVersionLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", false, GET, array( "int" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::ListaVersion( 
 			
			
			isset($_GET['id_tarifa'] ) ? $_GET['id_tarifa'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
