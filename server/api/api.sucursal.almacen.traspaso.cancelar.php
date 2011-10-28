<?php
/**
  * GET api/sucursal/almacen/traspaso/cancelar
  * Cancela un traspaso
  *
  * Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
  *
  *
  *
  **/

  class ApiSucursalAlmacenTraspasoCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_traspaso" => new ApiExposedProperty("id_traspaso", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::CancelarTraspasoAlmacen( 
 			
			
			isset($_GET['id_traspaso'] ) ? $_GET['id_traspaso'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
