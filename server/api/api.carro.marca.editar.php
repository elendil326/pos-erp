<?php
/**
  * GET api/carro/marca/editar
  * Edita una marca de un carro
  *
  * Edita una marca de un carro
  *
  *
  *
  **/

  class ApiCarroMarcaEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_marca_carro" => new ApiExposedProperty("id_marca_carro", true, GET, array( "int" )),
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"nombre_marca" => new ApiExposedProperty("nombre_marca", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::EditarMarca( 
 			
			
			isset($_GET['id_marca_carro'] ) ? $_GET['id_marca_carro'] : null,
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['nombre_marca'] ) ? $_GET['nombre_marca'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
