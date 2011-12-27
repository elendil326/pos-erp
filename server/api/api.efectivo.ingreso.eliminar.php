<?php
/**
  * GET api/efectivo/ingreso/eliminar
  * Elimina un ingreso
  *
  * Cancela un ingreso
  *
  *
  *
  **/

  class ApiEfectivoIngresoEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_ingreso" => new ApiExposedProperty("id_ingreso", true, GET, array( "int" )),
			"motivo_cancelacion" => new ApiExposedProperty("motivo_cancelacion", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = CargosYAbonosController::EliminarIngreso( 
 			
			
			isset($_GET['id_ingreso'] ) ? $_GET['id_ingreso'] : null,
			isset($_GET['motivo_cancelacion'] ) ? $_GET['motivo_cancelacion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
