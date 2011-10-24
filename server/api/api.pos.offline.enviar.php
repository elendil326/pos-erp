<?php
/**
  * POST api/pos/offline/enviar
  * Enviar compras o ventas de mostrador al servidor despues de una perdida de conectividad.
  *
  * Si un perdidad de conectividad sucediera, es responsabilidad del cliente registrar las ventas o compras realizadas desde que se perdio conectividad. Cuando se restablezca la conexcion se deberan enviar las ventas o compras. 
  *
  *
  *
  **/

  class ApiPosOfflineEnviar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"compras" => new ApiExposedProperty("compras", false, POST, array( "json" )),
			"ventas" => new ApiExposedProperty("ventas", false, POST, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = POSController::EnviarOffline( 
 			
			
			isset($_POST['compras'] ) ? $_POST['compras'] : null,
			isset($_POST['ventas'] ) ? $_POST['ventas'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
