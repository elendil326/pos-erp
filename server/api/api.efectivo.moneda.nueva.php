<?php
/**
  * GET api/efectivo/moneda/nueva
  * Crea una nueva moneda
  *
  * Crea una moneda, "pesos", "dolares", etc.
  *
  *
  *
  **/

  class ApiEfectivoMonedaNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"simbolo" => new ApiExposedProperty("simbolo", true, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::NuevaMoneda( 
 			
			
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['simbolo'] ) ? $_GET['simbolo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
