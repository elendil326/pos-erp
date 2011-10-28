<?php
/**
  * GET api/efectivo/billete/eliminar
  * Elimina un billete
  *
  * Desactiva un billete
  *
  *
  *
  **/

  class ApiEfectivoBilleteEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_billete" => new ApiExposedProperty("id_billete", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::EliminarBillete( 
 			
			
			isset($_GET['id_billete'] ) ? $_GET['id_billete'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
