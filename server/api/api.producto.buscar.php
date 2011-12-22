<?php
/**
  * GET api/producto/buscar
  * Buscar productos
  *
  * Buscar productos
  *
  *
  *
  **/

  class ApiProductoBuscar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"query" => new ApiExposedProperty("query", true, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Buscar( 
 			
			
			isset($_GET['query'] ) ? $_GET['query'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
