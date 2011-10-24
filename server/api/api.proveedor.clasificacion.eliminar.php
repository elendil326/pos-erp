<?php
/**
  * GET api/proveedor/clasificacion/eliminar
  * Elimina una clasificacion de proveedor
  *
  * Desactiva una clasificacion de proveedor
  *
  *
  *
  **/

  class ApiProveedorClasificacionEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_proveedor" => new ApiExposedProperty("id_clasificacion_proveedor", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProveedoresController::EliminarClasificacion( 
 			
			
			isset($_GET['id_clasificacion_proveedor'] ) ? $_GET['id_clasificacion_proveedor'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
