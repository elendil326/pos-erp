<?php
/**
  * GET api/carro/descargar
  * Descargar producto de un carro
  *
  * Descargar producto de un carro. El id de la sucursal se tomara de la sesion actual. La fecha se tomara del servidor. El almacen de la sucursal que realiza la operacion se vera afectada.
  *
  *
  *
  **/

  class ApiCarroDescargar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_carro" => new ApiExposedProperty("id_carro", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Descargar( 
 			
			
			isset($_GET['id_carro'] ) ? $_GET['id_carro'] : null,
			isset($_GET['productos'] ) ? json_decode($_GET['productos']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
