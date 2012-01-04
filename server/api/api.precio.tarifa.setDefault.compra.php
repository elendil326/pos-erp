<?php
/**
  * POST api/precio/tarifa/setDefault/compra
  * Asigna el default a una tarifa de compra
  *
  * Asigna el default a una tarifa de compra. La tarifa default es la que se usara en todas las compras a menos que el usuario indique otra tarifa.

Solo se puede elegir una tarifa de tipo compra.
  *
  *
  *
  **/

  class ApiPrecioTarifaSetDefaultCompra extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::CompraSetDefaultTarifa( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
