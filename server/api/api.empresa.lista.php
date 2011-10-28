<?php
/**
  * GET api/empresa/lista
  * Lista todas las empresas existentes.
  *
  * Mostrar? todas la empresas en el sistema, as? como sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
  *
  *
  *
  **/

  class ApiEmpresaLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"activa" => new ApiExposedProperty("activa", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EmpresasController::Lista( 
 			
			
			isset($_GET['activa'] ) ? $_GET['activa'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
