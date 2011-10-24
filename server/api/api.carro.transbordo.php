<?php
/**
  * GET api/carro/transbordo
  * Mover mercancia de un carro a otro.
  *
  * Mover mercancia de un carro a otro. 
UPDATE
Se movera parcial o totalmente la carga?
  *
  *
  *
  **/

  class ApiCarroTransbordo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_carro_origen" => new ApiExposedProperty("id_carro_origen", true, GET, array( "int" )),
			"id_carro_destino" => new ApiExposedProperty("id_carro_destino", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Transbordo( 
 			
			
			isset($_GET['id_carro_origen'] ) ? $_GET['id_carro_origen'] : null,
			isset($_GET['id_carro_destino'] ) ? $_GET['id_carro_destino'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
