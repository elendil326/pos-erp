<?php
/**
  * GET api/autorizaciones/gasto
  * Solicitud de autorizaci?ara realizar un gasto.
  *
  * La fecha de peticion se tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
  *
  *
  *
  **/

  class ApiAutorizacionesGasto extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"monto" => new ApiExposedProperty("monto", true, GET, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", true, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Gasto( 
 			
			
			isset($_GET['monto'] ) ? $_GET['monto'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
