<?php
/**
  * GET api/compras/cancelar
  * Cancela una compra
  *
  * Cancela una compra
  *
  *
  *
  **/

  class ApiComprasCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_compra" => new ApiExposedProperty("id_compra", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Cancelar( 
 			
			
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
