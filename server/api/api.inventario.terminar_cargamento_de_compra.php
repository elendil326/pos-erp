<?php
/**
  * GET api/inventario/terminar_cargamento_de_compra
  * ver transporte y fletes...
  *
  * ver transporte y fletes...
  *
  *
  *
  **/

  class ApiInventarioTerminarCargamentoDeCompra extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Terminar_cargamento_de_compra( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
