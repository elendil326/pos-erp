<?php
/**
  * GET api/servicios/clasificacion/eliminar
  * Elimina una clasificacion de servicio
  *
  * Elimina una clasificacion de servicio
  *
  *
  *
  **/

  class ApiServiciosClasificacionEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_servicio" => new ApiExposedProperty("id_clasificacion_servicio", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::EliminarClasificacion( 
 			
			
			isset($_GET['id_clasificacion_servicio'] ) ? $_GET['id_clasificacion_servicio'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
