<?php
/**
  * GET api/sucursal/caja/corte
  * Realiza un corte de caja
  *
  * Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
  *
  *
  *
  **/

  class ApiSucursalCajaCorte extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"billetes_dejados" => new ApiExposedProperty("billetes_dejados", true, GET, array( "json" )),
			"billetes_encontrados" => new ApiExposedProperty("billetes_encontrados", true, GET, array( "json" )),
			"saldo_real" => new ApiExposedProperty("saldo_real", true, GET, array( "float" )),
			"id_caja" => new ApiExposedProperty("id_caja", true, GET, array( "int" )),
			"saldo_final" => new ApiExposedProperty("saldo_final", true, GET, array( "float" )),
			"id_cajero" => new ApiExposedProperty("id_cajero", false, GET, array( "int" )),
			"id_cajero_nuevo" => new ApiExposedProperty("id_cajero_nuevo", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::CorteCaja( 
 			
			
			isset($_GET['billetes_dejados'] ) ? $_GET['billetes_dejados'] : null,
			isset($_GET['billetes_encontrados'] ) ? $_GET['billetes_encontrados'] : null,
			isset($_GET['saldo_real'] ) ? $_GET['saldo_real'] : null,
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['saldo_final'] ) ? $_GET['saldo_final'] : null,
			isset($_GET['id_cajero'] ) ? $_GET['id_cajero'] : null,
			isset($_GET['id_cajero_nuevo'] ) ? $_GET['id_cajero_nuevo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
