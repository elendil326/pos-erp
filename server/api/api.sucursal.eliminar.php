<?php
/**
  * GET api/sucursal/eliminar
  * Elimina una sucursal
  *
  * Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
  *
  *
  *
  **/

  class ApiSucursalEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::Eliminar( 
 			
			
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
