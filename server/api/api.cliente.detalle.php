<?php
/**
  * GET api/cliente/detalle
  * Obtener los detalles de un cliente.
  *
  * Obtener los detalles de un cliente.
  *
  *
  *
  **/

  class ApiClienteDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_cliente" => new ApiExposedProperty("id_cliente", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::Detalle( 
 			
			
			isset($_GET['id_cliente'] ) ? $_GET['id_cliente'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
