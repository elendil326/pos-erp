<?php
/**
  * GET api/carro/modelo/editar
  * Editar el modelo del carro
  *
  * Editar el modelo del carro
  *
  *
  *
  **/

  class ApiCarroModeloEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_modelo_carro" => new ApiExposedProperty("id_modelo_carro", true, GET, array( "int" )),
			"nombre_modelo_carro" => new ApiExposedProperty("nombre_modelo_carro", true, GET, array( "string" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::EditarModelo( 
 			
			
			isset($_GET['id_modelo_carro'] ) ? $_GET['id_modelo_carro'] : null,
			isset($_GET['nombre_modelo_carro'] ) ? $_GET['nombre_modelo_carro'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
