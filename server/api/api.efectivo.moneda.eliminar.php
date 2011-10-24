<?php
/**
  * GET api/efectivo/moneda/eliminar
  * Elimina una moneda
  *
  * Desactiva una moneda
  *
  *
  *
  **/

  class ApiEfectivoMonedaEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_moneda" => new ApiExposedProperty("id_moneda", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::EliminarMoneda( 
 			
			
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
