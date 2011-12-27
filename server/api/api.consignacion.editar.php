<?php
/**
  * GET api/consignacion/editar
  * Edita una consignacion
  *
  * Edita una consignacion, ya sea que agregue o quite productos a la misma. La fecha se toma del sistema.
  *
  *
  *
  **/

  class ApiConsignacionEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"agregar" => new ApiExposedProperty("agregar", true, GET, array( "bool" )),
			"id_consignacion" => new ApiExposedProperty("id_consignacion", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Editar( 
 			
			
			isset($_GET['agregar'] ) ? $_GET['agregar'] : null,
			isset($_GET['id_consignacion'] ) ? $_GET['id_consignacion'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
