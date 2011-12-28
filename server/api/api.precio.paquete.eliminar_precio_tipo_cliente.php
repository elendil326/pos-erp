<?php
/**
  * GET api/precio/paquete/eliminar_precio_tipo_cliente
  * Elimina la relacion del precio de un paquete con un tipo de cliente
  *
  * Elimina la relacion del precio de un paquete con un tipo de cliente
  *
  *
  *
  **/

  class ApiPrecioPaqueteEliminarPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
			"paquetes" => new ApiExposedProperty("paquetes", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_tipo_clientePaquete( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null,
			isset($_GET['paquetes'] ) ? json_decode($_GET['paquetes']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
