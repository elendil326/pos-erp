<?php
/**
  * GET api/precio/producto/eliminar_precio_usuario
  * Elimina la relacion del precio de un producto con un usuario
  *
  * Elimina la relacion del precio de un producto con un usuario
  *
  *
  *
  **/

  class ApiPrecioProductoEliminarPrecioUsuario extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_usuarioProducto( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
