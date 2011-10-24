<?php
/**
  * POST api/efectivo/ingreso/concepto/editar
  * Editar un concepto de ingreso
  *
  * Edita un concepto de ingreso
  *
  *
  *
  **/

  class ApiEfectivoIngresoConceptoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_concepto_ingreso" => new ApiExposedProperty("id_concepto_ingreso", true, POST, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"monto" => new ApiExposedProperty("monto", false, POST, array( "float" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EditarConceptoIngreso( 
 			
			
			isset($_POST['id_concepto_ingreso'] ) ? $_POST['id_concepto_ingreso'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['monto'] ) ? $_POST['monto'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
