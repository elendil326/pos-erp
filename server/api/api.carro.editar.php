<?php
/**
  * GET api/carro/editar
  * Edita un carro
  *
  * Edita la informacion de un carro
  *
  *
  *
  **/

  class ApiCarroEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_carro" => new ApiExposedProperty("id_carro", true, GET, array( "int" )),
			"km_por_litro" => new ApiExposedProperty("km_por_litro", false, GET, array( "float" )),
			"combustible" => new ApiExposedProperty("combustible", false, GET, array( "float" )),
			"kilometros" => new ApiExposedProperty("kilometros", false, GET, array( "float" )),
			"num_neumaticos" => new ApiExposedProperty("num_neumaticos", false, GET, array( "int" )),
			"codigo" => new ApiExposedProperty("codigo", false, GET, array( "string" )),
			"matricula" => new ApiExposedProperty("matricula", false, GET, array( "string" )),
			"imagen" => new ApiExposedProperty("imagen", false, GET, array( "string" )),
			"id_estado" => new ApiExposedProperty("id_estado", false, GET, array( "int" )),
			"id_modelo_vehiculo" => new ApiExposedProperty("id_modelo_vehiculo", false, GET, array( "int" )),
			"id_localizacion" => new ApiExposedProperty("id_localizacion", false, GET, array( "int" )),
			"id_marca_carro" => new ApiExposedProperty("id_marca_carro", false, GET, array( "int" )),
			"id_tipo_carro" => new ApiExposedProperty("id_tipo_carro", false, GET, array( "int" )),
			"ids_empresas" => new ApiExposedProperty("ids_empresas", false, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Editar( 
 			
			
			isset($_GET['id_carro'] ) ? $_GET['id_carro'] : null,
			isset($_GET['km_por_litro'] ) ? $_GET['km_por_litro'] : null,
			isset($_GET['combustible'] ) ? $_GET['combustible'] : null,
			isset($_GET['kilometros'] ) ? $_GET['kilometros'] : null,
			isset($_GET['num_neumaticos'] ) ? $_GET['num_neumaticos'] : null,
			isset($_GET['codigo'] ) ? $_GET['codigo'] : null,
			isset($_GET['matricula'] ) ? $_GET['matricula'] : null,
			isset($_GET['imagen'] ) ? $_GET['imagen'] : null,
			isset($_GET['id_estado'] ) ? $_GET['id_estado'] : null,
			isset($_GET['id_modelo_vehiculo'] ) ? $_GET['id_modelo_vehiculo'] : null,
			isset($_GET['id_localizacion'] ) ? $_GET['id_localizacion'] : null,
			isset($_GET['id_marca_carro'] ) ? $_GET['id_marca_carro'] : null,
			isset($_GET['id_tipo_carro'] ) ? $_GET['id_tipo_carro'] : null,
			isset($_GET['ids_empresas'] ) ? $_GET['ids_empresas'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  