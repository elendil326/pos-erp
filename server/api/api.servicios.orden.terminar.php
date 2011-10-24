<?php
/**
  * POST api/servicios/orden/terminar
  * Dar por terminada una orden
  *
  * Dar por terminada una orden, cuando el cliente satisface el ultimo pago
  *
  *
  *
  **/

  class ApiServiciosOrdenTerminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_orden" => new ApiExposedProperty("id_orden", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::TerminarOrden( 
 			
			
			isset($_POST['id_orden'] ) ? $_POST['id_orden'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
