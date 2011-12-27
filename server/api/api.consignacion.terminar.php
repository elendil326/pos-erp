<?php
/**
  * GET api/consignacion/terminar
  * Lista todas las empresas existentes.
  *
  * Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
  *
  *
  *
  **/

  class ApiConsignacionTerminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_consignacion" => new ApiExposedProperty("id_consignacion", true, GET, array( "int" )),
			"productos_actuales" => new ApiExposedProperty("productos_actuales", true, GET, array( "json" )),
			"motivo" => new ApiExposedProperty("motivo", false, GET, array( "string" )),
			"tipo_pago" => new ApiExposedProperty("tipo_pago", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Terminar( 
 			
			
			isset($_GET['id_consignacion'] ) ? $_GET['id_consignacion'] : null,
			isset($_GET['productos_actuales'] ) ? $_GET['productos_actuales'] : null,
			isset($_GET['motivo'] ) ? $_GET['motivo'] : null,
			isset($_GET['tipo_pago'] ) ? $_GET['tipo_pago'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
