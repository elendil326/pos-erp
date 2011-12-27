<?php
/**
  * POST api/servicios/orden/seguimiento
  * Realizar un seguimiento a una orden de servicio existente
  *
  * Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
  *
  *
  *
  **/

  class ApiServiciosOrdenSeguimiento extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_orden_de_servicio" => new ApiExposedProperty("id_orden_de_servicio", true, POST, array( "int" )),
			"nota" => new ApiExposedProperty("nota", true, POST, array( "string" )),
			"id_localizacion" => new ApiExposedProperty("id_localizacion", false, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::SeguimientoOrden( 
 			
			
			isset($_POST['id_orden_de_servicio'] ) ? $_POST['id_orden_de_servicio'] : null,
			isset($_POST['nota'] ) ? $_POST['nota'] : null,
			isset($_POST['id_localizacion'] ) ? $_POST['id_localizacion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
