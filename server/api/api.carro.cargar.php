<?php
/**
  * GET api/carro/cargar
  * Realizar un cargamento a un carro
  *
  * Realizar un cargamento a un carro. El id de la sucursal sera tomada de la sesion actual. La fecha sera tomada del servidor. El inventario de la sucursal que carga el camion se vera afectado por esta operacion.
  *
  *
  *
  **/

  class ApiCarroCargar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
			"id_carro" => new ApiExposedProperty("id_carro", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Cargar( 
 			
			
			isset($_GET['productos'] ) ? $_GET['productos'] : null,
			isset($_GET['id_carro'] ) ? $_GET['id_carro'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
