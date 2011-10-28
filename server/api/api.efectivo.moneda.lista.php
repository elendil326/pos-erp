<?php
/**
  * GET api/efectivo/moneda/lista
  * Lista las moendas
  *
  * Lista las monedas de una instancia
  *
  *
  *
  **/

  class ApiEfectivoMonedaLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"orden" => new ApiExposedProperty("orden", false, GET, array( "json" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::ListaMoneda( 
 			
			
			isset($_GET['orden'] ) ? $_GET['orden'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
