<?php
/**
  * GET api/compras/detalle
  * Muestra el detalle de una compra
  *
  * Muestra el detalle de una compra
  *
  *
  *
  **/

  class ApiComprasDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_compra" => new ApiExposedProperty("id_compra", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ComprasController::Detalle( 
 			
			
			isset($_GET['id_compra'] ) ? $_GET['id_compra'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
