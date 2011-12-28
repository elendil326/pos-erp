<?php
/**
  * GET api/consignacion/nueva
  * Iniciar una orden de consignaci?n
  *
  * Iniciar una orden de consignaci?n. La fecha sera tomada del servidor.
  *
  *
  *
  **/

  class ApiConsignacionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"fecha_termino" => new ApiExposedProperty("fecha_termino", true, GET, array( "string" )),
			"folio" => new ApiExposedProperty("folio", true, GET, array( "string" )),
			"id_consignatario" => new ApiExposedProperty("id_consignatario", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
			"tipo_consignacion" => new ApiExposedProperty("tipo_consignacion", true, GET, array( "string" )),
			"fecha_envio_programada" => new ApiExposedProperty("fecha_envio_programada", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Nueva( 
 			
			
			isset($_GET['fecha_termino'] ) ? $_GET['fecha_termino'] : null,
			isset($_GET['folio'] ) ? $_GET['folio'] : null,
			isset($_GET['id_consignatario'] ) ? $_GET['id_consignatario'] : null,
			isset($_GET['productos'] ) ? json_decode($_GET['productos']) : null,
			isset($_GET['tipo_consignacion'] ) ? $_GET['tipo_consignacion'] : null,
			isset($_GET['fecha_envio_programada'] ) ? $_GET['fecha_envio_programada'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
