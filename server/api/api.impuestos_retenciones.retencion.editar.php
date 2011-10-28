<?php
/**
  * GET api/impuestos_retenciones/retencion/editar
  * Edita una retencion
  *
  * Edita la informacion de una retencion
  *
  *
  *
  **/

  class ApiImpuestosRetencionesRetencionEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_retencion" => new ApiExposedProperty("id_retencion", true, GET, array( "int" )),
			"es_monto" => new ApiExposedProperty("es_monto", false, GET, array( "bool" )),
			"monto_porcentaje" => new ApiExposedProperty("monto_porcentaje", false, GET, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ImpuestosYRetencionesController::EditarRetencion( 
 			
			
			isset($_GET['id_retencion'] ) ? $_GET['id_retencion'] : null,
			isset($_GET['es_monto'] ) ? $_GET['es_monto'] : null,
			isset($_GET['monto_porcentaje'] ) ? $_GET['monto_porcentaje'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
