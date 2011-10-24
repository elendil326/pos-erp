<?php
/**
  * GET api/reporte/nuevo
  * genera un reporte a la medida
  *
  * Un usuario con grupo 1 o con el permiso puede generar reportes a la medida.
  *
  *
  *
  **/

  class ApiReporteNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ReportesController::Nuevo( 
 			
		
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
