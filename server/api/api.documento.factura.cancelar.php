<?php
/**
  * GET api/documento/factura/cancelar
  * Cancela una factura
  *
  * Cancela una factura.
  *
  *
  *
  **/

  class ApiDocumentoFacturaCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_folio" => new ApiExposedProperty("id_folio", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::CancelarFactura( 
 			
			
			isset($_GET['id_folio'] ) ? $_GET['id_folio'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
