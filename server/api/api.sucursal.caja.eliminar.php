<?php
/**
  * GET api/sucursal/caja/eliminar
  * Elimina una caja
  *
  * Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
  *
  *
  *
  **/

  class ApiSucursalCajaEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_caja" => new ApiExposedProperty("id_caja", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EliminarCaja( 
 			
			
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
