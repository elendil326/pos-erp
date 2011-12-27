<?php
/**
  * GET api/sucursal/tipo_almacen/eliminar
  * Elimina un tipo de almacen
  *
  * Elimina un tipo de almacen
  *
  *
  *
  **/

  class ApiSucursalTipoAlmacenEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_almacen" => new ApiExposedProperty("id_tipo_almacen", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EliminarTipo_almacen( 
 			
			
			isset($_GET['id_tipo_almacen'] ) ? $_GET['id_tipo_almacen'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
