<?php
/**
  * GET api/inventario/compras_sucursal
  * Lista todas las compras de una sucursal.
  *
  * Lista todas las compras de una sucursal.
  *
  *
  *
  **/

  class ApiInventarioComprasSucursal extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Compras_sucursal( 
 			
			
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
