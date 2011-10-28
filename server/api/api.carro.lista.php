<?php
/**
  * GET api/carro/lista
  * Lista de carros en la empresa
  *
  * Lista todos los carros de la instancia. Puede filtrarse por empresa, por su estado y ordenarse por sus atributos
  *
  *
  *
  **/

  class ApiCarroLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_estado" => new ApiExposedProperty("id_estado", false, GET, array( "int" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Lista( 
 			
			
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_estado'] ) ? $_GET['id_estado'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
