<?php
/**
  * GET api/precio/paquete/editar_precio_usuario
  * Edita la relacion de precio con uno o varios paquetes para un usuario
  *
  * Edita la relacion de precio con uno o varios paquetes para un usuario
  *
  *
  *
  **/

  class ApiPrecioPaqueteEditarPrecioUsuario extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "int" )),
			"paquetes_precios_utilidad" => new ApiExposedProperty("paquetes_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Editar_precio_usuarioPaquete( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['paquetes_precios_utilidad'] ) ? json_decode($_GET['paquetes_precios_utilidad']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
