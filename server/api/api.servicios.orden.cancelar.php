<?php
/**
  * GET api/servicios/orden/cancelar
  * Cancela una orden de servicio
  *
  * Cancela una orden de servicio. Cuando se cancela un servicio, se cancelan tambien las ventas en las que aparece este servicio.
  *
  *
  *
  **/

  class ApiServiciosOrdenCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_orden_de_servicio" => new ApiExposedProperty("id_orden_de_servicio", true, GET, array( "int" )),
			"motivo_cancelacion" => new ApiExposedProperty("motivo_cancelacion", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::CancelarOrden( 
 			
			
			isset($_GET['id_orden_de_servicio'] ) ? $_GET['id_orden_de_servicio'] : null,
			isset($_GET['motivo_cancelacion'] ) ? $_GET['motivo_cancelacion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
