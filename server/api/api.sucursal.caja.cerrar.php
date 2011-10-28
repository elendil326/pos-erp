<?php
/**
  * GET api/sucursal/caja/cerrar
  * Hace un corte en los flujos de dinero de esta caja
  *
  * Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
  *
  *
  *
  **/

  class ApiSucursalCajaCerrar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_caja" => new ApiExposedProperty("id_caja", true, GET, array( "int" )),
			"saldo_real" => new ApiExposedProperty("saldo_real", true, GET, array( "float" )),
			"id_cajero" => new ApiExposedProperty("id_cajero", false, GET, array( "int" )),
			"billetes" => new ApiExposedProperty("billetes", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::CerrarCaja( 
 			
			
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['saldo_real'] ) ? $_GET['saldo_real'] : null,
			isset($_GET['id_cajero'] ) ? $_GET['id_cajero'] : null,
			isset($_GET['billetes'] ) ? $_GET['billetes'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
