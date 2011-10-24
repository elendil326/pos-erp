<?php
/**
  * GET api/carro/registrar_llegada
  * Registra llegada del carro
  *
  * Registra la llegada de un carro a una sucursal. La fecha sera tomada del servidor
  *
  *
  *
  **/

  class ApiCarroRegistrarLlegada extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_carro" => new ApiExposedProperty("id_carro", true, GET, array( "int" )),
			"fecha_llegada" => new ApiExposedProperty("fecha_llegada", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Registrar_llegada( 
 			
			
			isset($_GET['id_carro'] ) ? $_GET['id_carro'] : null,
			isset($_GET['fecha_llegada'] ) ? $_GET['fecha_llegada'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
