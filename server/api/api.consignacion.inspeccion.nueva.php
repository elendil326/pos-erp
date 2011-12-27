<?php
/**
  * GET api/consignacion/inspeccion/nueva
  * Calendariza una nueva inspeccion
  *
  * Registra en que fecha se le hara una inspeccion a un cliente con consignacion 
  *
  *
  *
  **/

  class ApiConsignacionInspeccionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"fecha_revision" => new ApiExposedProperty("fecha_revision", true, GET, array( "string" )),
			"id_consignacion" => new ApiExposedProperty("id_consignacion", true, GET, array( "int" )),
			"id_inspector" => new ApiExposedProperty("id_inspector", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::NuevaInspeccion( 
 			
			
			isset($_GET['fecha_revision'] ) ? $_GET['fecha_revision'] : null,
			isset($_GET['id_consignacion'] ) ? $_GET['id_consignacion'] : null,
			isset($_GET['id_inspector'] ) ? $_GET['id_inspector'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
