<?php
/**
  * POST api/precio/tarifa/setDefault/venta
  * Selecciona como default para las ventas una tarifa de venta
  *
  * Selecciona como default para las ventas una tarifa de venta. Esta tarifa sera usada para todas las ventas a menos que el usuario indique otra tarifa de venta.

Solo puede asignarse como default de ventas una tarifa de tipo venta
  *
  *
  *
  **/

  class ApiPrecioTarifaSetDefaultVenta extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::VentaSetDefaultTarifa( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
