<?php
/**
  * GET api/consignacion/cancelar
  * Cancela una consignacion
  *
  * Este metodo solo debe ser usado cuando no se ha vendido ningun producto de la consginacion y todos seran devueltos
  *
  *
  *
  **/

  class ApiConsignacionCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"productos_almacen" => new ApiExposedProperty("productos_almacen", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ConsignacionesController::Cancelar( 
 			
			
			isset($_GET['productos_almacen'] ) ? json_decode($_GET['productos_almacen']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
