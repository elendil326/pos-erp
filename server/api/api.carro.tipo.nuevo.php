<?php
/**
  * GET api/carro/tipo/nuevo
  * Agrega un nuevo tipo de carro
  *
  * Agrega un nuevo tipo de carro ( camion, camioneta, etc)
  *
  *
  *
  **/

  class ApiCarroTipoNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre_tipo" => new ApiExposedProperty("nombre_tipo", true, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::NuevoTipo( 
 			
			
			isset($_GET['nombre_tipo'] ) ? $_GET['nombre_tipo'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
