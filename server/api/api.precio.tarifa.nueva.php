<?php
/**
  * POST api/precio/tarifa/nueva
  * Crea una nueva tarifa
  *
  * Crea una nueva tarifa 
  *
  *
  *
  **/

  class ApiPrecioTarifaNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_moneda" => new ApiExposedProperty("id_moneda", true, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", true, POST, array( "string" )),
			"activa" => new ApiExposedProperty("activa", false, POST, array( "bool" )),
			"default" => new ApiExposedProperty("default", false, POST, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::NuevaTarifa( 
 			
			
			isset($_POST['id_moneda'] ) ? $_POST['id_moneda'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['tipo_tarifa'] ) ? $_POST['tipo_tarifa'] : null,
			isset($_POST['activa'] ) ? $_POST['activa'] : null,
			isset($_POST['default'] ) ? $_POST['default'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
