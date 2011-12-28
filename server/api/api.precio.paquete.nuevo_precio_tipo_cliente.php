<?php
/**
  * GET api/precio/paquete/nuevo_precio_tipo_cliente
  * Relaciona un tipo de cliente con paquetes a un precio o utilidad determinados
  *
  * Relaciona un tipo de cliente con paquetes a un precio o utilidad determinados
  *
  *
  *
  **/

  class ApiPrecioPaqueteNuevoPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
			"paquetes_precios_utilidad" => new ApiExposedProperty("paquetes_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Nuevo_precio_tipo_clientePaquete( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null,
			isset($_GET['paquetes_precios_utilidad'] ) ? json_decode($_GET['paquetes_precios_utilidad']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
