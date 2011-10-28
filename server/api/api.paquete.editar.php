<?php
/**
  * GET api/paquete/editar
  * Edita la informacion de un paquete
  *
  * Edita la informacion de un paquete
  *
  *
  *
  **/

  class ApiPaqueteEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_paquete" => new ApiExposedProperty("id_paquete", true, GET, array( "int" )),
			"foto_paquete" => new ApiExposedProperty("foto_paquete", false, GET, array( "string" )),
			"productos" => new ApiExposedProperty("productos", false, GET, array( "json" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"servicios" => new ApiExposedProperty("servicios", false, GET, array( "json" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PaquetesController::Editar( 
 			
			
			isset($_GET['id_paquete'] ) ? $_GET['id_paquete'] : null,
			isset($_GET['foto_paquete'] ) ? $_GET['foto_paquete'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['servicios'] ) ? $_GET['servicios'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
