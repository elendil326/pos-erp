<?php
/**
  * GET api/carro/detalle
  * Ver los detalles e historial de un carro especifico
  *
  * Ver los detalles e historial de un carro especifico
  *
  *
  *
  **/

  class ApiCarroDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_carro" => new ApiExposedProperty("id_carro", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Detalle( 
 			
			
			isset($_GET['id_carro'] ) ? $_GET['id_carro'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
