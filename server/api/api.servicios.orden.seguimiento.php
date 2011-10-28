<?php
/**
  * GET api/servicios/orden/seguimiento
  * Realizar un seguimiento a una orden de servicio existente
  *
  * Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
  *
  *
  *
  **/

  class ApiServiciosOrdenSeguimiento extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"estado" => new ApiExposedProperty("estado", true, GET, array( "string" )),
			"id_localizacion" => new ApiExposedProperty("id_localizacion", true, GET, array( "int" )),
			"id_orden_de_servicio" => new ApiExposedProperty("id_orden_de_servicio", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::SeguimientoOrden( 
 			
			
			isset($_GET['estado'] ) ? $_GET['estado'] : null,
			isset($_GET['id_localizacion'] ) ? $_GET['id_localizacion'] : null,
			isset($_GET['id_orden_de_servicio'] ) ? $_GET['id_orden_de_servicio'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
