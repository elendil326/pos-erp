<?php
/**
  * GET api/sucursal/caja/editar
  * Edita la informacion de una caja
  *
  * Edita la informacion de una caja
  *
  *
  *
  **/

  class ApiSucursalCajaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_caja" => new ApiExposedProperty("id_caja", true, GET, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, GET, array( "string" )),
			"token" => new ApiExposedProperty("token", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EditarCaja( 
 			
			
			isset($_GET['id_caja'] ) ? $_GET['id_caja'] : null,
			isset($_GET['descripcion'] ) ? $_GET['descripcion'] : null,
			isset($_GET['token'] ) ? $_GET['token'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
