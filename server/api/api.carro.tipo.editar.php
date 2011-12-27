<?php
/**
  * GET api/carro/tipo/editar
  * Edita un tipo de carro
  *
  * Edita un registro de tipo de carro (camion, camioneta, etc)
  *
  *
  *
  **/

  class ApiCarroTipoEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tipo_carro" => new ApiExposedProperty("id_tipo_carro", true, GET, array( "int" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"nombre_tipo_carro" => new ApiExposedProperty("nombre_tipo_carro", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::EditarTipo( 
 			
			
			isset($_GET['id_tipo_carro'] ) ? $_GET['id_tipo_carro'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['nombre_tipo_carro'] ) ? $_GET['nombre_tipo_carro'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
