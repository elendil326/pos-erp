<?php
/**
  * GET api/ventas/detalle
  * Lista el detalle de una venta
  *
  * Lista el detalle de una venta, se puede ordenar de acuerdo a sus atributos.
  *
  *
  *
  **/

  class ApiVentasDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_venta" => new ApiExposedProperty("id_venta", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Detalle( 
 			
			
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
