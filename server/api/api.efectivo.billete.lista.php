<?php
/**
  * GET api/efectivo/billete/lista
  * Lista los billetes
  *
  * Lista los billetes de una instancia
  *
  *
  *
  **/

  class ApiEfectivoBilleteLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "json" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::ListaBillete( 
 			
			
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
