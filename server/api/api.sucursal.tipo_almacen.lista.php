<?php
/**
  * GET api/sucursal/tipo_almacen/lista
  * Imprime la lista de tipos de almacen
  *
  * Imprime la lista de tipos de almacen
  *
  *
  *
  **/

  class ApiSucursalTipoAlmacenLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ListaTipo_almacen( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
