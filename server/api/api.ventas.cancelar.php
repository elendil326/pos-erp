<?php
/**
  * GET api/ventas/cancelar
  * Cancelar una venta
  *
  * Metodo que cancela una venta
  *
  *
  *
  **/

  class ApiVentasCancelar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_venta" => new ApiExposedProperty("id_venta", true, GET, array( "int" )),
			"billetes" => new ApiExposedProperty("billetes", false, GET, array( "json" )),
			"id_caja" => new ApiExposedProperty("id_caja", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Cancelar( 
 			
			
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null,
			isset($_GET['billetes'] ) ? $_GET['billetes'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
