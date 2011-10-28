<?php
/**
  * GET api/sucursal/caja/abrir
  * Abrir una caja, esta caja se asociara a esta sesion.
  *
  * Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
  *
  *
  *
  **/

  class ApiSucursalCajaAbrir extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_caja" => new ApiExposedProperty("id_caja", true, GET, array( "int" )),
			"saldo" => new ApiExposedProperty("saldo", true, GET, array( "float" )),
			"client_token" => new ApiExposedProperty("client_token", true, GET, array( "string" )),
			"control_billetes" => new ApiExposedProperty("control_billetes", true, GET, array( "bool" )),
			"billetes" => new ApiExposedProperty("billetes", true, GET, array( "json" )),
			"id_cajero" => new ApiExposedProperty("id_cajero", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::AbrirCaja( 
 			
			
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['saldo'] ) ? $_GET['saldo'] : null,
			isset($_GET['client_token'] ) ? $_GET['client_token'] : null,
			isset($_GET['control_billetes'] ) ? $_GET['control_billetes'] : null,
			isset($_GET['billetes'] ) ? $_GET['billetes'] : null,
			isset($_GET['id_cajero'] ) ? $_GET['id_cajero'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
