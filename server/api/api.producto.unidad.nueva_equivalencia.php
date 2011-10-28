<?php
/**
  * GET api/producto/unidad/nueva_equivalencia
  * Crea una equivalencia entre una unidad y otra
  *
  * Crea un registro de la equivalencia entre una unidad y otra. Ejemplo: 1 kg = 2.204 lb
  *
  *
  *
  **/

  class ApiProductoUnidadNuevaEquivalencia extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_unidad" => new ApiExposedProperty("id_unidad", true, GET, array( "int" )),
			"id_unidades" => new ApiExposedProperty("id_unidades", true, GET, array( "int" )),
			"equivalencia" => new ApiExposedProperty("equivalencia", true, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Nueva_equivalenciaUnidad( 
 			
			
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null,
			isset($_GET['id_unidades'] ) ? $_GET['id_unidades'] : null,
			isset($_GET['equivalencia'] ) ? $_GET['equivalencia'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
