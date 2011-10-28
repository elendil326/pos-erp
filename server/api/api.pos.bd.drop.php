<?php
/**
  * POST api/pos/bd/drop
  * Limpiar la base de datos y establecer los valores default
  *
  * Metodo que elimina todos los registros en la base de datos, especialmente util para hacer pruebas unitarias. Este metodo NO estara disponible al publico.
  *
  *
  *
  **/

  class ApiPosBdDrop extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = POSController::DropBd( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
