<?php
/**
  * GET api/precio/servicio/editar_precio_usuario
  * Edita la relacion de precio con uno o varios servicios para un usuario
  *
  * Edita la relacion de precio con uno o varios servicios para un usuario
  *
  *
  *
  **/

  class ApiPrecioServicioEditarPrecioUsuario extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "int" )),
			"servicios_precios_utilidad" => new ApiExposedProperty("servicios_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Editar_precio_usuarioServicio( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['servicios_precios_utilidad'] ) ? $_GET['servicios_precios_utilidad'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
