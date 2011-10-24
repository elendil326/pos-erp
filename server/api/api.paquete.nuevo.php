<?php
/**
  * GET api/paquete/nuevo
  * Crea un nuevo paquete
  *
  * Agrupa productos y/o servicios en un paquete
  *
  *
  *
  **/

  class ApiPaqueteNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"empresas" => new ApiExposedProperty("empresas", true, GET, array( "json" )),
			"sucursales" => new ApiExposedProperty("sucursales", true, GET, array( "json" )),
			"productos" => new ApiExposedProperty("productos", false, GET, array( "json" )),
			"sericios" => new ApiExposedProperty("sericios", false, GET, array( "json" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"foto_paquete" => new ApiExposedProperty("foto_paquete", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PaquetesController::Nuevo( 
 			
			
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['empresas'] ) ? $_GET['empresas'] : null,
			isset($_GET['sucursales'] ) ? $_GET['sucursales'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null,
			isset($_GET['sericios'] ) ? $_GET['sericios'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['foto_paquete'] ) ? $_GET['foto_paquete'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
