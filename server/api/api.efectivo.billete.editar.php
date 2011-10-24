<?php
/**
  * GET api/efectivo/billete/editar
  * Edita la informacion de un billete
  *
  * Edita la informacion de un billete
  *
  *
  *
  **/

  class ApiEfectivoBilleteEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_billete" => new ApiExposedProperty("id_billete", true, GET, array( "int" )),
			"valor" => new ApiExposedProperty("valor", false, GET, array( "int" )),
			"foto_billete" => new ApiExposedProperty("foto_billete", false, GET, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, GET, array( "string" )),
			"id_moneda" => new ApiExposedProperty("id_moneda", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EfectivoController::EditarBillete( 
 			
			
			isset($_GET['id_billete'] ) ? $_GET['id_billete'] : null,
			isset($_GET['valor'] ) ? $_GET['valor'] : null,
			isset($_GET['foto_billete'] ) ? $_GET['foto_billete'] : null,
			isset($_GET['nombre'] ) ? $_GET['nombre'] : null,
			isset($_GET['id_moneda'] ) ? $_GET['id_moneda'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
