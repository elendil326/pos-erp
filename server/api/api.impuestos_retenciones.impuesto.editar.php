<?php
/**
  * GET api/impuestos_retenciones/impuesto/editar
  * Edita un impuesto
  *
  * Edita la informacion de un impuesto
  *
  *
  *
  **/

  class ApiImpuestosRetencionesImpuestoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_impuesto" => new ApiExposedProperty("id_impuesto", true, GET, array( "int" )),
			"es_monto" => new ApiExposedProperty("es_monto", false, GET, array( "bool" )),
			"monto_porcentaje" => new ApiExposedProperty("monto_porcentaje", false, GET, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ImpuestosYRetencionesController::EditarImpuesto( 
 			
			
			isset($_GET['id_impuesto'] ) ? $_GET['id_impuesto'] : null,
			isset($_GET['es_monto'] ) ? $_GET['es_monto'] : null,
			isset($_GET['monto_porcentaje'] ) ? $_GET['monto_porcentaje'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
