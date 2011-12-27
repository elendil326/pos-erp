<?php
/**
  * GET api/precio/servicio/eliminar_precio_tipo_cliente
  * Elimina la relacion del precio de un servicio con un tipo de cliente
  *
  * Elimina la relacion del precio de un servicio con un tipo de cliente
  *
  *
  *
  **/

  class ApiPrecioServicioEliminarPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
			"servicios" => new ApiExposedProperty("servicios", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_tipo_clienteServicio( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null,
			isset($_GET['servicios'] ) ? $_GET['servicios'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
