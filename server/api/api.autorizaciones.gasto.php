<?php
/**
  * POST api/autorizaciones/gasto
  * Solicitud de autorizaci?n para realizar un gasto.
  *
  * En caso de que el usuario no tenga persmiso para realizar gasto, puede pedir una autorizacion para registrar un gasto. 
  *
  *
  *
  **/

  class ApiAutorizacionesGasto extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, POST, array( "string" )),
			"monto" => new ApiExposedProperty("monto", true, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Gasto( 
 			
			
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['monto'] ) ? $_POST['monto'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
