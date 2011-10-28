<?php
/**
  * GET api/producto/unidad/editar
  * Edita una unidad
  *
  * Este metodo modifica la informacion de una unidad
  *
  *
  *
  **/

  class ApiProductoUnidadEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_unidad_convertible" => new ApiExposedProperty("id_unidad_convertible", true, GET, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::EditarUnidad( 
 			
			
			isset($_GET['id_unidad_convertible'] ) ? $_GET['id_unidad_convertible'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
