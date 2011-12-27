<?php
/**
  * GET api/servicios/orden/detalle
  * Ver los detalles de una orden de servicio
  *
  * Ver los detalles de una orden de servicio. Puede ordenarse por sus atributos. Los detalles de la orden de servicio son los seguimientos que tiene esa orden as? como el estado y sus fechas de orden y de entrega.
  *
  *
  *
  **/

  class ApiServiciosOrdenDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_orden" => new ApiExposedProperty("id_orden", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::DetalleOrden( 
 			
			
			isset($_GET['id_orden'] ) ? $_GET['id_orden'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
