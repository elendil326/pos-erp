<?php
/**
  * GET api/producto/unidad/eliminar_equivalencia
  * Elimina una equivalencia
  *
  * Elimina una equivalencia entre dos unidades.
Ejemplo: 1 kg = 2.204 lb
  *
  *
  *
  **/

  class ApiProductoUnidadEliminarEquivalencia extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_unidades" => new ApiExposedProperty("id_unidades", true, GET, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Eliminar_equivalenciaUnidad( 
 			
			
			isset($_GET['id_unidades'] ) ? $_GET['id_unidades'] : null,
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
