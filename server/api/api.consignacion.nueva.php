<?php
/**
  * GET api/consignacion/nueva
  * Iniciar una orden de consignaci?n
  *
  * Iniciar una orden de consignaci?n. La fecha sera tomada del servidor.
  *
  *
  *
  **/

  class ApiConsignacionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
			"id_consignatario" => new ApiExposedProperty("id_consignatario", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Nueva( 
 			
			
			isset($_GET['productos'] ) ? $_GET['productos'] : null,
			isset($_GET['id_consignatario'] ) ? $_GET['id_consignatario'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
