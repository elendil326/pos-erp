<?php
/**
  * GET api/carro/marca/nuevo
  * Agrega una nueva marca de carro
  *
  * Agrega una nueva marca de carro
  *
  *
  *
  **/

  class ApiCarroMarcaNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre_marca" => new ApiExposedProperty("nombre_marca", true, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::NuevoMarca( 
 			
			
			isset($_GET['nombre_marca'] ) ? $_GET['nombre_marca'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
