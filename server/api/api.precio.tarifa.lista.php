<?php
/**
  * GET api/precio/tarifa/lista
  * Lista las tarifas existentes
  *
  * Lista las tarifas existentes. Se puede ordenar de acuerdo a los atributos de la tabla y se puede filtrar por el tipo de tarifa, la moneda que usa y por el valor de activa.
  *
  *
  *
  **/

  class ApiPrecioTarifaLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"activa" => new ApiExposedProperty("activa", false, GET, array( "bool" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, GET, array( "int" )),
			"orden" => new ApiExposedProperty("orden", false, GET, array( "string" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::ListaTarifa( 
 			
			
			isset($_GET['activa'] ) ? $_GET['activa'] : null,
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null,
			isset($_GET['orden'] ) ? $_GET['orden'] : null,
			isset($_GET['tipo_tarifa'] ) ? $_GET['tipo_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
