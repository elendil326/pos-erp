<?php
/**
  * GET api/precio/producto/eliminar_precio_tipo_cliente
  * Elimina la relacion del precio de un producto con un tipo de cliente
  *
  * Elimina la relacion del precio de un producto con un tipo de cliente
  *
  *
  *
  **/

  class ApiPrecioProductoEliminarPrecioTipoCliente extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_cliente" => new ApiExposedProperty("id_tipo_cliente", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_tipo_clienteProducto( 
 			
			
			isset($_GET['id_tipo_cliente'] ) ? $_GET['id_tipo_cliente'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
