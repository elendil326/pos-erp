<?php
/**
  * POST api/autorizaciones/responder
  * Responde a una auorizaci?n en estado pendiente.
  *
  * Responde a una autorizaci?n en estado pendiente. Este m?todo no se puede aplicar a una autorizaci?n ya resuelta.
  *
  *
  *
  **/

  class ApiAutorizacionesResponder extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"aceptar" => new ApiExposedProperty("aceptar", true, POST, array( "bool" )),
			"id_autorizacion" => new ApiExposedProperty("id_autorizacion", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Responder( 
 			
			
			isset($_POST['aceptar'] ) ? $_POST['aceptar'] : null,
			isset($_POST['id_autorizacion'] ) ? $_POST['id_autorizacion'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
