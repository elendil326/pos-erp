<?php
/**
  * GET api/precio/servicio/nuevo_precio_tipo_cliente
  * Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
  *
  * Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
  *
  *
  *
  **/

  class ApiPrecioServicioNuevoPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
			"servicios_precios_utilidad" => new ApiExposedProperty("servicios_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Nuevo_precio_tipo_clienteServicio( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null,
			isset($_GET['servicios_precios_utilidad'] ) ? json_decode($_GET['servicios_precios_utilidad']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
