<?php
/**
  * GET api/impuestos_retenciones/impuesto/lista
  * Lista los impuestos
  *
  * Listas los impuestos
  *
  *
  *
  **/

  class ApiImpuestosRetencionesImpuestoLista extends ApiHandler {
  

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
 		$this->response = ImpuestosYRetencionesController::ListaImpuesto( 
 			
			
			isset($_GET['ordenar'] ) ? $_GET['ordenar'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
