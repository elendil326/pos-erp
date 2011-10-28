<?php
/**
  * GET api/reporte/detalle
  * Obtener un detalle de un reporte
  *
  * Obtener un detalle de un reporte
  *
  *
  *
  **/

  class ApiReporteDetalle extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_reporte" => new ApiExposedProperty("id_reporte", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ReportesController::Detalle( 
 			
			
			isset($_GET['id_reporte'] ) ? $_GET['id_reporte'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
