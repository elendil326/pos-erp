<?php
/**
  * GET api/impuestos_retenciones/retencion/lista
  * Lista las retenciones
  *
  * Lista las retenciones
  *
  *
  *
  **/

  class ApiImpuestosRetencionesRetencionLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"ordenar" => new ApiExposedProperty("ordenar", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ImpuestosYRetencionesController::ListaRetencion( 
 			
			
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
