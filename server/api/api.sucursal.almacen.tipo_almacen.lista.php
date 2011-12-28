<?php
/**
  * GET api/sucursal/almacen/tipo_almacen/lista
  * Imprime la lista de tipos de almacen
  *
  * Imprime la lista de tipos de almacen
  *
  *
  *
  **/

  class ApiSucursalAlmacenTipoAlmacenLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::ListaTipo_almacenAlmacen( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
