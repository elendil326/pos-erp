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
			"id_orden" => new ApiExposedProperty("id_orden", true, POST, array( "int" )),
			"tipo_venta" => new ApiExposedProperty("tipo_venta", true, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"saldo" => new ApiExposedProperty("saldo", false, POST, array( "float" )),
			"tipo_de_pago" => new ApiExposedProperty("tipo_de_pago", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::TerminarOrden( 
 			
			
			isset($_POST['id_orden'] ) ? $_POST['id_orden'] : null,
			isset($_POST['tipo_venta'] ) ? $_POST['tipo_venta'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['saldo'] ) ? $_POST['saldo'] : null,
			isset($_POST['tipo_de_pago'] ) ? $_POST['tipo_de_pago'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
