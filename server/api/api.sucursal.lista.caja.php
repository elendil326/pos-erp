<?php
/**
  * GET api/sucursal/lista/caja
  * Lista las cajas
  *
  * Lista las cajas. Se puede filtrar por la sucursal a la que pertenecen.
  *
  *
  *
  **/

  class ApiSucursalListaCaja extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"activa" => new ApiExposedProperty("activa", false, GET, array( "bool" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::CajaLista( 
 			
			
			isset($_GET['activa'] ) ? $_GET['activa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
