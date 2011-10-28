<?php
/**
  * GET api/carro/modelo/nuevo
  * Crea un nuevo modelo de carro
  *
  * Crea un nuevo modelo de carro
  *
  *
  *
  **/

  class ApiCarroModeloNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre_modelo" => new ApiExposedProperty("nombre_modelo", true, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::NuevoModelo( 
 			
			
			isset($_GET['nombre_modelo'] ) ? $_GET['nombre_modelo'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
