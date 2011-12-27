<?php
/**
  * GET api/producto/unidad/eliminar
  * Elimina una unidad
  *
  * Descativa una unidad para que no sea usada por otro metodo
  *
  *
  *
  **/

  class ApiProductoUnidadEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_unidad" => new ApiExposedProperty("id_unidad", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::EliminarUnidad( 
 			
			
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
