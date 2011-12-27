<?php
/**
  * POST api/personal/rol/nuevo
  * Crea un nuevo grupo de usuarios
  *
  * Crea un nuevo grupo de usuarios. Se asignaran los permisos de este grupo al momento de su creacion.
  *
  *
  *
  **/

  class ApiPersonalRolNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"salario" => new ApiExposedProperty("salario", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::NuevoRol( 
 			
			
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['salario'] ) ? $_POST['salario'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
