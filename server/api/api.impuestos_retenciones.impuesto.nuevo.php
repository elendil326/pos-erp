<?php
/**
  * POST api/impuestos_retenciones/impuesto/nuevo
  * Crear un nuevo impuesto.
  *
  * Crear un nuevo impuesto.
  *
  *
  *
  **/

  class ApiImpuestosRetencionesImpuestoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"es_monto" => new ApiExposedProperty("es_monto", true, POST, array( "bool" )),
			"monto_porcentaje" => new ApiExposedProperty("monto_porcentaje", true, POST, array( "float" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ImpuestosYRetencionesController::NuevoImpuesto( 
 			
			
			isset($_POST['es_monto'] ) ? $_POST['es_monto'] : null,
			isset($_POST['monto_porcentaje'] ) ? $_POST['monto_porcentaje'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
