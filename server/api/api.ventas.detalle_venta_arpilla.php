<?php
/**
  * GET api/ventas/detalle_venta_arpilla
  * Muestra el detalle de una venta por arpilla
  *
  * Muestra el detalle de una venta por arpilla. Este metodo no muestra los productos de una venta, sino los detalles del embarque de la misma. Para ver los productos de una venta refierase a api/ventas/detalle
  *
  *
  *
  **/

  class ApiVentasDetalleVentaArpilla extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_venta" => new ApiExposedProperty("id_venta", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Detalle_venta_arpilla( 
 			
			
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
