<?php
/**
  * GET api/precio/producto/editar_precio_tipo_cliente
  * Edita la relacion de precio de uno o varios productos con un tipo de cliente
  *
  * Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo edita la relacion de un precio a uno o varios productos con un tipo de cliente.
  *
  *
  *
  **/

  class ApiPrecioProductoEditarPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_cliente" => new ApiExposedProperty("id_clasificacion_cliente", true, GET, array( "int" )),
			"productos_precios_utilidad" => new ApiExposedProperty("productos_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Editar_precio_tipo_clienteProducto( 
 			
			
			isset($_GET['id_clasificacion_cliente'] ) ? $_GET['id_clasificacion_cliente'] : null,
			isset($_GET['productos_precios_utilidad'] ) ? $_GET['productos_precios_utilidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
