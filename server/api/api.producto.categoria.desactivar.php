<?php
/**
  * GET api/producto/categoria/desactivar
  * Desactiva una categoria
  *
  * Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
  *
  *
  *
  **/

  class ApiProductoCategoriaDesactivar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_categoria" => new ApiExposedProperty("id_categoria", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::DesactivarCategoria( 
 			
			
			isset($_GET['id_categoria'] ) ? $_GET['id_categoria'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
