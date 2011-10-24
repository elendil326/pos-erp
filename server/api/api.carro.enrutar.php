<?php
/**
  * GET api/carro/enrutar
  * Enviar un cargamento
  *
  * Enviar un cargamento. No necesariamente debe tener cargamento. Seria excelente calcular el kilometraje. La sucursal origen sera tomada de la sesion actual.
  *
  *
  *
  **/

  class ApiCarroEnrutar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_carro" => new ApiExposedProperty("id_carro", true, GET, array( "int" )),
			"id_sucursal_destino" => new ApiExposedProperty("id_sucursal_destino", true, GET, array( "int" )),
			"fecha_salida" => new ApiExposedProperty("fecha_salida", true, GET, array( "string" )),
			"fecha_llegada_tentativa" => new ApiExposedProperty("fecha_llegada_tentativa", true, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Enrutar( 
 			
			
			isset($_GET['id_carro'] ) ? $_GET['id_carro'] : null,
			isset($_GET['id_sucursal_destino'] ) ? $_GET['id_sucursal_destino'] : null,
			isset($_GET['fecha_salida'] ) ? $_GET['fecha_salida'] : null,
			isset($_GET['fecha_llegada_tentativa'] ) ? $_GET['fecha_llegada_tentativa'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
