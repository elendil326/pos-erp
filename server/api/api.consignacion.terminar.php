<?php
/**
  * GET api/consignacion/terminar
  * Lista todas las empresas existentes.
  *
  * Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
  *
  *
  *
  **/

  class ApiConsignacionTerminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_consignacion" => new ApiExposedProperty("id_consignacion", true, GET, array( "int" )),
			"motivo" => new ApiExposedProperty("motivo", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Terminar( 
 			
			
			isset($_GET['id_consignacion'] ) ? $_GET['id_consignacion'] : null,
			isset($_GET['motivo'] ) ? $_GET['motivo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
