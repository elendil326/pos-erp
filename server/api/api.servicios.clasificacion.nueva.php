<?php
/**
  * GET api/servicios/clasificacion/nueva
  * Genera una nueva clasificacion de servicio
  *
  * Genera una nueva clasificacion de servicio
  *
  *
  *
  **/

  class ApiServiciosClasificacionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"garantia" => new ApiExposedProperty("garantia", false, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"margen_utilidad" => new ApiExposedProperty("margen_utilidad", false, GET, array( "float" )),
			"descuento" => new ApiExposedProperty("descuento", false, GET, array( "float" )),
			"activa" => new ApiExposedProperty("activa", false, GET, array( "bool" )),
			"impuestos" => new ApiExposedProperty("impuestos", false, GET, array( "json" )),
			"retenciones" => new ApiExposedProperty("retenciones", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::NuevaClasificacion( 
 			
			
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['garantia'] ) ? $_GET['garantia'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['margen_utilidad'] ) ? $_GET['margen_utilidad'] : null,
			isset($_GET['descuento'] ) ? $_GET['descuento'] : null,
			isset($_GET['activa'] ) ? $_GET['activa'] : null,
			isset($_GET['impuestos'] ) ? $_GET['impuestos'] : null,
			isset($_GET['retenciones'] ) ? $_GET['retenciones'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
