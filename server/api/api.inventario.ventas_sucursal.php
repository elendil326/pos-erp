<?php
/**
  * GET api/inventario/ventas_sucursal
  * Lista las ventas de una sucursal
  *
  * Lista las ventas de una sucursal.
  *
  *
  *
  **/

  class ApiInventarioVentasSucursal extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Ventas_sucursal( 
 			
			
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
