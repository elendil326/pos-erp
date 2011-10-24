<?php
/**
  * GET api/efectivo/gasto/concepto/eliminar
  * Deshabilita un concepto de gasto
  *
  * Deshabilita un concepto de gasto
Update :Se deber?de tomar tambi?de la sesi?l id del usuario y fecha de la ultima modificaci?
  *
  *
  *
  **/

  class ApiEfectivoGastoConceptoEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_concepto_gasto" => new ApiExposedProperty("id_concepto_gasto", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EliminarConceptoGasto( 
 			
			
			isset($_GET['id_concepto_gasto'] ) ? $_GET['id_concepto_gasto'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
