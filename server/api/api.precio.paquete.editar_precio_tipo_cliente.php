<?php
/**
  * GET api/precio/paquete/editar_precio_tipo_cliente
  * Edita la relacion de precio con uno o varios paquetes para un tipo de cliente
  *
  * Edita la relacion de precio con uno o varios paquetes para un tipo de cliente
  *
  *
  *
  **/

  class ApiPrecioPaqueteEditarPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"paquetes_precios_utilidad" => new ApiExposedProperty("paquetes_precios_utilidad", true, GET, array( "json" )),
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Editar_precio_tipo_clientePaquete( 
 			
			
			isset($_GET['paquetes_precios_utilidad'] ) ? $_GET['paquetes_precios_utilidad'] : null,
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
