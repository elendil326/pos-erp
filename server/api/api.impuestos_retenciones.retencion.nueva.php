<?php
/**
  * GET api/impuestos_retenciones/retencion/nueva
  * Crea una nueva retencion
  *
  * Crea una nueva retencion
  *
  *
  *
  **/

  class ApiImpuestosRetencionesRetencionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"es_monto" => new ApiExposedProperty("es_monto", true, GET, array( "float" )),
			"monto_porcentaje" => new ApiExposedProperty("monto_porcentaje", true, GET, array( "float" )),
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ImpuestosYRetencionesController::NuevaRetencion( 
 			
			
			isset($_GET['es_monto'] ) ? $_GET['es_monto'] : null,
			isset($_GET['monto_porcentaje'] ) ? $_GET['monto_porcentaje'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
