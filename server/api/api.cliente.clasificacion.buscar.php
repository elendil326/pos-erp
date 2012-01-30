<?php
/**
  * GET api/cliente/clasificacion/buscar
  * Lista de las clasificaciones existentes
  *
  * Busca una clasificaci?n por clave, nombre o descripci?n
  *
  *
  *
  **/

  class ApiClienteClasificacionBuscar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"limit" => new ApiExposedProperty("limit", false, GET, array( "int" )),
			"page" => new ApiExposedProperty("page", false, GET, array( "int" )),
			"query" => new ApiExposedProperty("query", false, GET, array( "string" )),
			"start" => new ApiExposedProperty("start", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::BuscarClasificacion( 
 			
			
			isset($_GET['limit'] ) ? $_GET['limit'] : null,
			isset($_GET['page'] ) ? $_GET['page'] : null,
			isset($_GET['query'] ) ? $_GET['query'] : null,
			isset($_GET['start'] ) ? $_GET['start'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
