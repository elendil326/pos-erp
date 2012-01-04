<?php
/**
  * POST api/precio/version/editar
  * Edita una version de una tarifa
  *
  * Edita la informacion basica de una version. El nombre, la fecha de inicio y la fecha de fin.

?Sera necesario permitir que el usuario cambie una version de una tarifa a otra tarifa?
  *
  *
  *
  **/

  class ApiPrecioVersionEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_version" => new ApiExposedProperty("id_version", true, POST, array( "int" )),
			"fecha_fin" => new ApiExposedProperty("fecha_fin", false, POST, array( "string" )),
			"fecha_inicio" => new ApiExposedProperty("fecha_inicio", false, POST, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::EditarVersion( 
 			
			
			isset($_POST['id_version'] ) ? $_POST['id_version'] : null,
			isset($_POST['fecha_fin'] ) ? $_POST['fecha_fin'] : null,
			isset($_POST['fecha_inicio'] ) ? $_POST['fecha_inicio'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
