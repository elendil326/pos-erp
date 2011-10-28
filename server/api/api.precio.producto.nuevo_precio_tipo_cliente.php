<?php
/**
  * GET api/precio/producto/nuevo_precio_tipo_cliente
  * Asigna un precio a un producto para determinado tipo de cliente
  *
  * Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo relaciona un precio a uno o varios productos con un tipo de cliente.
  *
  *
  *
  **/

  class ApiPrecioProductoNuevoPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
			"productos_precios_utilidad" => new ApiExposedProperty("productos_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Nuevo_precio_tipo_clienteProducto( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null,
			isset($_GET['productos_precios_utilidad'] ) ? $_GET['productos_precios_utilidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
