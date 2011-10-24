<?php
/**
  * GET api/servicios/eliminar
  * Desactiva un servicio
  *
  * Da de baja un servicio que ofrece una empresa
  *
  *
  *
  **/

  class ApiServiciosEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_servicio" => new ApiExposedProperty("id_servicio", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::Eliminar( 
 			
			
			isset($_GET['id_servicio'] ) ? $_GET['id_servicio'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
