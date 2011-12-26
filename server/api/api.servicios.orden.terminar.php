<?php
/**
  * POST api/servicios/orden/terminar
  * Dar por terminada una orden
  *
  * Dar por terminada una orden, al momento de terminarse una orden se genera una venta, por lo tanto, al terminar la orden hay que especificar datos de la misma.
  *
  *
  *
  **/

  class ApiServiciosOrdenTerminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"tipo_venta" => new ApiExposedProperty("tipo_venta", true, POST, array( "string" )),
			"id_orden" => new ApiExposedProperty("id_orden", true, POST, array( "int" )),
			"saldo" => new ApiExposedProperty("saldo", false, POST, array( "float" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"tipo_de_pago" => new ApiExposedProperty("tipo_de_pago", false, POST, array( "string" )),
			"cheques" => new ApiExposedProperty("cheques", false, POST, array( "json" )),
			"billetes_pago" => new ApiExposedProperty("billetes_pago", false, POST, array( "json" )),
			"billetes_cambio" => new ApiExposedProperty("billetes_cambio", false, POST, array( "json" )),
			"id_venta_caja" => new ApiExposedProperty("id_venta_caja", false, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::TerminarOrden( 
 			
			
			isset($_POST['tipo_venta'] ) ? $_POST['tipo_venta'] : null,
			isset($_POST['id_orden'] ) ? $_POST['id_orden'] : null,
			isset($_POST['saldo'] ) ? $_POST['saldo'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['tipo_de_pago'] ) ? $_POST['tipo_de_pago'] : null,
			isset($_POST['cheques'] ) ? $_POST['cheques'] : null,
			isset($_POST['billetes_pago'] ) ? $_POST['billetes_pago'] : null,
			isset($_POST['billetes_cambio'] ) ? $_POST['billetes_cambio'] : null,
			isset($_POST['id_venta_caja'] ) ? $_POST['id_venta_caja'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
