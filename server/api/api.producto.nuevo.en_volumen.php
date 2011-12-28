<?php
/**
  * POST api/producto/nuevo/en_volumen
  * Agregar productos en volumen mediante un archivo CSV.
  *
  * Agregar productos en volumen mediante un archivo CSV.
  *
  *
  *
  **/

  class ApiProductoNuevoEnVolumen extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"productos" => new ApiExposedProperty("productos", true, POST, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::En_volumenNuevo( 
 			
			
			isset($_POST['productos'] ) ? json_decode($_POST['productos']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
