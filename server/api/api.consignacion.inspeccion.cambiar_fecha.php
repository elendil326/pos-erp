<?php
/**
  * GET api/consignacion/inspeccion/cambiar_fecha
  * Cambia la fecha de una inspeccion 
  *
  * Usese este metodo cuando se posterga o se adelanta una inspeccion
  *
  *
  *
  **/

  class ApiConsignacionInspeccionCambiarFecha extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_inspeccion" => new ApiExposedProperty("id_inspeccion", true, GET, array( "int" )),
			"nueva_fecha" => new ApiExposedProperty("nueva_fecha", true, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Cambiar_fechaInspeccion( 
 			
			
			isset($_GET['id_inspeccion'] ) ? $_GET['id_inspeccion'] : null,
			isset($_GET['nueva_fecha'] ) ? $_GET['nueva_fecha'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
