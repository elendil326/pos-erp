<?php
/**
  * GET api/personal/rol/lista
  * Lista los roles
  *
  * Lista los roles, se puede filtrar por empresa y ordenar por sus atributos
  *
  *
  *
  **/

  class ApiPersonalRolLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"orden" => new ApiExposedProperty("orden", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::ListaRol( 
 			
			
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
