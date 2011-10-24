<?php
/**
  * GET api/autorizaciones/detalle
  * Muestra la informacion detallada de una autorizacion.
  *
  * Muestra la informacion detallada de una autorizacion.
  *
  *
  *
  **/

  class ApiAutorizacionesDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_autorizacion	" => new ApiExposedProperty("id_autorizacion	", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Detalle( 
 			
			
			isset($_GET['id_autorizacion	'] ) ? $_GET['id_autorizacion	'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
