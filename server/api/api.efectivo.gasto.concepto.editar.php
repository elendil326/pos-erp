<?php
/**
  * GET api/efectivo/gasto/concepto/editar
  * Edita un concepto de gasto
  *
  * Edita la informaci?n de un concepto de gasto

Update : Se deber?a de tomar de la sesi?n el id del usuario que hiso la ultima modificaci?n y la fecha.
  *
  *
  *
  **/

  class ApiEfectivoGastoConceptoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_concepto_gasto" => new ApiExposedProperty("id_concepto_gasto", true, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"monto" => new ApiExposedProperty("monto", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EditarConceptoGasto( 
 			
			
			isset($_GET['id_concepto_gasto'] ) ? $_GET['id_concepto_gasto'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['monto'] ) ? $_GET['monto'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
