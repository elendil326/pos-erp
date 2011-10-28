<?php
/**
  * GET api/producto/desactivar
  * Desactiva un producto
  *
  * Este metodo sirve para dar de baja un producto
  *
  *
  *
  **/

  class ApiProductoDesactivar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_producto" => new ApiExposedProperty("id_producto", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Desactivar( 
 			
			
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
