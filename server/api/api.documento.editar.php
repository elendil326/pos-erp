<?php
/**
  * GET api/documento/editar
  * Edita un documento
  *
  * Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
  *
  *
  *
  **/

  class ApiDocumentoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_documento" => new ApiExposedProperty("id_documento", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::Editar( 
 			
			
			isset($_GET['id_documento'] ) ? $_GET['id_documento'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
