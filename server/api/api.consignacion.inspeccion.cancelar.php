<?php
/**
  * GET api/consignacion/inspeccion/cancelar
  * Cancela una inspeccion que aun no ha sido registrada
  *
  * Este metodo sirve para cancelar una inspeccion que aun no ha sido registrada. Sirve cuando se cancela una orden de consignacion y por consiguiente se tienen que cancelar las inspecciones programadas para esa consignacion.
  *
  *
  *
  **/

  class ApiConsignacionInspeccionCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_inspeccion" => new ApiExposedProperty("id_inspeccion", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::CancelarInspeccion( 
 			
			
			isset($_GET['id_inspeccion'] ) ? $_GET['id_inspeccion'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
