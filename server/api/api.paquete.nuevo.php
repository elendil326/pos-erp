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
	protected function GetRequest()
	{
		$this->request = array(	
			"empresas" => new ApiExposedProperty("empresas", true, GET, array( "json" )),
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"sucursales" => new ApiExposedProperty("sucursales", true, GET, array( "json" )),
			"costo_estandar" => new ApiExposedProperty("costo_estandar", false, GET, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"foto_paquete" => new ApiExposedProperty("foto_paquete", false, GET, array( "string" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"precio" => new ApiExposedProperty("precio", false, GET, array( "float" )),
			"productos" => new ApiExposedProperty("productos", false, GET, array( "json" )),
			"servicios" => new ApiExposedProperty("servicios", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PaquetesController::Nuevo( 
 			
			
			isset($_GET['empresas'] ) ? json_decode($_GET['empresas']) : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['sucursales'] ) ? json_decode($_GET['sucursales']) : null,
			isset($_GET['costo_estandar'] ) ? $_GET['costo_estandar'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['foto_paquete'] ) ? $_GET['foto_paquete'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['precio'] ) ? $_GET['precio'] : null,
			isset($_GET['productos'] ) ? json_decode($_GET['productos']) : null,
			isset($_GET['servicios'] ) ? json_decode($_GET['servicios']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
