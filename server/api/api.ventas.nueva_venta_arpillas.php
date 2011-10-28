<?php
/**
  * GET api/ventas/nueva_venta_arpillas
  * Realiza una nueva venta por arpillas
  *
  * Realiza una nueva venta por arpillas. Este m?todo tiene que llamarse en conjunto con el metodo api/ventas/nueva.
  *
  *
  *
  **/

  class ApiVentasNuevaVentaArpillas extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"peso_por_arpilla" => new ApiExposedProperty("peso_por_arpilla", true, GET, array( "float" )),
			"merma_por_arpilla" => new ApiExposedProperty("merma_por_arpilla", true, GET, array( "float" )),
			"arpillas" => new ApiExposedProperty("arpillas", true, GET, array( "float" )),
			"peso_origen" => new ApiExposedProperty("peso_origen", true, GET, array( "float" )),
			"fecha_origen" => new ApiExposedProperty("fecha_origen", true, GET, array( "string" )),
			"peso_destino" => new ApiExposedProperty("peso_destino", true, GET, array( "float" )),
			"id_venta" => new ApiExposedProperty("id_venta", true, GET, array( "int" )),
			"productor" => new ApiExposedProperty("productor", false, GET, array( "string" )),
			"numero_de_viaje" => new ApiExposedProperty("numero_de_viaje", false, GET, array( "string" )),
			"folio" => new ApiExposedProperty("folio", false, GET, array( "string" )),
			"total_origen" => new ApiExposedProperty("total_origen", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = VentasController::Nueva_venta_arpillas( 
 			
			
			isset($_GET['peso_por_arpilla'] ) ? $_GET['peso_por_arpilla'] : null,
			isset($_GET['merma_por_arpilla'] ) ? $_GET['merma_por_arpilla'] : null,
			isset($_GET['arpillas'] ) ? $_GET['arpillas'] : null,
			isset($_GET['peso_origen'] ) ? $_GET['peso_origen'] : null,
			isset($_GET['fecha_origen'] ) ? $_GET['fecha_origen'] : null,
			isset($_GET['peso_destino'] ) ? $_GET['peso_destino'] : null,
			isset($_GET['id_venta'] ) ? $_GET['id_venta'] : null,
			isset($_GET['productor'] ) ? $_GET['productor'] : null,
			isset($_GET['numero_de_viaje'] ) ? $_GET['numero_de_viaje'] : null,
			isset($_GET['folio'] ) ? $_GET['folio'] : null,
			isset($_GET['total_origen'] ) ? $_GET['total_origen'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
