<?php
/**
  * GET api/paquete/detalle
  * Muestra el detalle de un paquete
  *
  * Muestra los productos y/o servicios englobados en este paquete as?omo las sucursales y las empresas donde lo ofrecen
  *
  *
  *
  **/

  class ApiPaqueteDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_paquete" => new ApiExposedProperty("id_paquete", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PaquetesController::Detalle( 
 			
			
			isset($_GET['id_paquete'] ) ? $_GET['id_paquete'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
