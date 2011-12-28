<?php
/**
  * GET api/carro/nuevo
  * Crea un nuevo carro
  *
  * Crea un nuevo carro. La fecha de creacion sera tomada del servidor.
  *
  *
  *
  **/

  class ApiCarroNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_estado" => new ApiExposedProperty("id_estado", true, GET, array( "int" )),
			"id_localizacion" => new ApiExposedProperty("id_localizacion", true, GET, array( "int" )),
			"id_marca_carro" => new ApiExposedProperty("id_marca_carro", true, GET, array( "int" )),
			"id_modelo_vehiculo" => new ApiExposedProperty("id_modelo_vehiculo", true, GET, array( "int" )),
			"id_tipo_carro" => new ApiExposedProperty("id_tipo_carro", true, GET, array( "int" )),
			"imagen" => new ApiExposedProperty("imagen", true, GET, array( "string" )),
			"codigo" => new ApiExposedProperty("codigo", false, GET, array( "string" )),
			"combustible" => new ApiExposedProperty("combustible", false, GET, array( "float" )),
			"ids_empresas" => new ApiExposedProperty("ids_empresas", false, GET, array( "json" )),
			"kilometros" => new ApiExposedProperty("kilometros", false, GET, array( "float" )),
			"km_por_litro" => new ApiExposedProperty("km_por_litro", false, GET, array( "float" )),
			"matricula" => new ApiExposedProperty("matricula", false, GET, array( "string" )),
			"num_neumaticos" => new ApiExposedProperty("num_neumaticos", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = TransportacionYFletesController::Nuevo( 
 			
			
			isset($_GET['id_estado'] ) ? $_GET['id_estado'] : null,
			isset($_GET['id_localizacion'] ) ? $_GET['id_localizacion'] : null,
			isset($_GET['id_marca_carro'] ) ? $_GET['id_marca_carro'] : null,
			isset($_GET['id_modelo_vehiculo'] ) ? $_GET['id_modelo_vehiculo'] : null,
			isset($_GET['id_tipo_carro'] ) ? $_GET['id_tipo_carro'] : null,
			isset($_GET['imagen'] ) ? $_GET['imagen'] : null,
			isset($_GET['codigo'] ) ? $_GET['codigo'] : null,
			isset($_GET['combustible'] ) ? $_GET['combustible'] : null,
			isset($_GET['ids_empresas'] ) ? json_decode($_GET['ids_empresas']) : null,
			isset($_GET['kilometros'] ) ? $_GET['kilometros'] : null,
			isset($_GET['km_por_litro'] ) ? $_GET['km_por_litro'] : null,
			isset($_GET['matricula'] ) ? $_GET['matricula'] : null,
			isset($_GET['num_neumaticos'] ) ? $_GET['num_neumaticos'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
