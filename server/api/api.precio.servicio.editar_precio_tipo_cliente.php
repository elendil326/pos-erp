<?php
/**
  * GET api/precio/servicio/editar_precio_tipo_cliente
  * Edita la relacion de precio con uno o varios servicios para un tipo de cliente
  *
  * Edita la relacion de precio con uno o varios servicios para un tipo de cliente
  *
  *
  *
  **/

  class ApiPrecioServicioEditarPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "json" )),
			"servicios_precios_utilidad" => new ApiExposedProperty("servicios_precios_utilidad", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Editar_precio_tipo_clienteServicio( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? json_decode($_GET['id_tipo_cliente']) : null,
			isset($_GET['servicios_precios_utilidad'] ) ? $_GET['servicios_precios_utilidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
