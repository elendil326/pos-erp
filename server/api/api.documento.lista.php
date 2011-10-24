<?php
/**
  * GET api/documento/lista
  * Listar documentos en el sistema.
  *
  * Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
  *
  *
  *
  **/

  class ApiDocumentoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"activos" => new ApiExposedProperty("activos", true, GET, array( "bool" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::Lista( 
 			
			
			isset($_GET['activos'] ) ? $_GET['activos'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
