<?php
/**
  * GET api/autorizaciones/gasto
  * Solicitud de autorizaci?n para realizar un gasto.
  *
  * La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
  *
  *
  *
  **/

  class ApiAutorizacionesGasto extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, GET, array( "string" )),
			"monto" => new ApiExposedProperty("monto", true, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = AutorizacionesController::Gasto( 
 			
			
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['monto'] ) ? $_GET['monto'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
