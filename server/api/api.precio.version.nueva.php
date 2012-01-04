<?php
/**
  * POST api/precio/version/nueva
  * Crea una nueva version para una tarifa
  *
  * Crea una nueva version para una tarifa.

Si no se reciben fechas de inicio o fin, se da por hecho que la version no caduca. Si solo se recibe fecha de fin, se toma como la fecha de inicio la fecha actual del servidor. Si solo se recibe fecha de inicio, se toma como fecha final la maxima permitida por MySQL (9999-12-31 23:59:59).

La version por default de una tarifa no puede caducar.

Las tarifas solo pueden tener una version activa.
  *
  *
  *
  **/

  class ApiPrecioVersionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_tarifa" => new ApiExposedProperty("id_tarifa", true, POST, array( "int" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"activa" => new ApiExposedProperty("activa", false, POST, array( "bool" )),
			"default" => new ApiExposedProperty("default", false, POST, array( "bool" )),
			"fecha_fin" => new ApiExposedProperty("fecha_fin", false, POST, array( "string" )),
			"fecha_inicio" => new ApiExposedProperty("fecha_inicio", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::NuevaVersion( 
 			
			
			isset($_POST['id_tarifa'] ) ? $_POST['id_tarifa'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['activa'] ) ? $_POST['activa'] : null,
			isset($_POST['default'] ) ? $_POST['default'] : null,
			isset($_POST['fecha_fin'] ) ? $_POST['fecha_fin'] : null,
			isset($_POST['fecha_inicio'] ) ? $_POST['fecha_inicio'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
