<?php
/**
  * GET api/producto/unidad/lista_equivalencia
  * Lista las equivalencias existentes
  *
  * Lista las equivalencias existentes. Se puede ordenar por sus atributos
  *
  *
  *
  **/

  class ApiProductoUnidadListaEquivalencia extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Lista_equivalenciaUnidad( 
 			
			
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
