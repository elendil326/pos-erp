<?php
/**
  * GET api/efectivo/moneda/editar
  * Edita la informacion de una moneda
  *
  * Edita la informacion de una moneda
  *
  *
  *
  **/

  class ApiEfectivoMonedaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_moneda" => new ApiExposedProperty("id_moneda", true, GET, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"simbolo" => new ApiExposedProperty("simbolo", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::EditarMoneda( 
 			
			
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['simbolo'] ) ? $_GET['simbolo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
