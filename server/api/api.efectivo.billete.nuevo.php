<?php
/**
  * GET api/efectivo/billete/nuevo
  * Crea un nuevo billete
  *
  * Crea un nuevo billete, se puede utilizar para monedas tambien.
  *
  *
  *
  **/

  class ApiEfectivoBilleteNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, GET, array( "string" )),
			"valor" => new ApiExposedProperty("valor", true, GET, array( "int" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", true, GET, array( "int" )),
			"foto_billete" => new ApiExposedProperty("foto_billete", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::NuevoBillete( 
 			
			
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['valor'] ) ? $_GET['valor'] : null,
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null,
			isset($_GET['foto_billete'] ) ? $_GET['foto_billete'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
