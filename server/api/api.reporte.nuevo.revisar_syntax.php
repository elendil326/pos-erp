<?php
/**
  * GET api/reporte/nuevo/revisar_syntax
  * Revisar la syntaxis de un JSON que resulta de crear un nuevo reporte.
  *
  * Revisar la syntaxis de un JSON que resulta de crear un nuevo reporte.
  *
  *
  *
  **/

  class ApiReporteNuevoRevisarSyntax extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nuevo_reporte" => new ApiExposedProperty("nuevo_reporte", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ReportesController::Revisar_syntaxNuevo( 
 			
			
			isset($_GET['nuevo_reporte'] ) ? $_GET['nuevo_reporte'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
