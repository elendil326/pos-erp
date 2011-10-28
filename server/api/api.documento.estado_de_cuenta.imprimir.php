<?php
/**
  * GET api/documento/estado_de_cuenta/imprimir
  * Imprime un estado de cuenta
  *
  * Imprime un estado de cuenta de un cliente.
  *
  *
  *
  **/

  class ApiDocumentoEstadoDeCuentaImprimir extends ApiHandler {
  

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
 		$this->response = DocumentosController::ImprimirEstado_de_cuenta( 
 			
			
			isset($_GET['id_cliente'] ) ? $_GET['id_cliente'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
