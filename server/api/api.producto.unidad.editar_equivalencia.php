<?php
/**
  * GET api/producto/unidad/editar_equivalencia
  * Edita la equivalencia entre dos unidades
  *
  * Edita la equivalencia entre dos unidades.
1 kg = 2.204 lb
  *
  *
  *
  **/

  class ApiProductoUnidadEditarEquivalencia extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"equivalencia" => new ApiExposedProperty("equivalencia", true, GET, array( "float" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", true, GET, array( "int" )),
			"id_unidades" => new ApiExposedProperty("id_unidades", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Editar_equivalenciaUnidad( 
 			
			
			isset($_GET['equivalencia'] ) ? $_GET['equivalencia'] : null,
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null,
			isset($_GET['id_unidades'] ) ? $_GET['id_unidades'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
