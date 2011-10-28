<?php
/**
  * GET api/efectivo/ingreso/concepto/eliminar
  * Deshabilita un concepto de ingreso
  *
  * Deshabilita un concepto de ingreso

Update :Se deber?a tambi?n obtener de la sesi?n el id del usuario y fecha de la ultima modificaci?n.
  *
  *
  *
  **/

  class ApiEfectivoIngresoConceptoEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_concepto_ingreso" => new ApiExposedProperty("id_concepto_ingreso", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EliminarConceptoIngreso( 
 			
			
			isset($_GET['id_concepto_ingreso'] ) ? $_GET['id_concepto_ingreso'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
