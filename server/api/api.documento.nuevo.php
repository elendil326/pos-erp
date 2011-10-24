<?php
/**
  * GET api/documento/nuevo
  * Crea un nuevo documento
  *
  * Crea un nuevo documento.

Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
  *
  *
  *
  **/

  class ApiDocumentoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = DocumentosController::Nuevo( 
 			
		
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
