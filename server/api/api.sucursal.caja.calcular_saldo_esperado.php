<?php
/**
  * GET api/sucursal/caja/calcular_saldo_esperado
  * Calcula el saldo esperado para una caja
  *
  * Calcula el saldo esperado para una caja a partir de los cortes que le han realizado, la fecha de apertura y la fecha en que se realiza el calculo. La caja sera tomada de la sesion, la fecha sera tomada del servidor. Para poder realizar este metodo, la caja tiene que estar abierta
  *
  *
  *
  **/

  class ApiSucursalCajaCalcularSaldoEsperado extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_caja" => new ApiExposedProperty("id_caja", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::Calcular_saldo_esperadoCaja( 
 			
			
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
