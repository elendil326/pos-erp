<?php
/**
  * POST api/personal/rol/editar
  * Edita un grupo
  *
  * Edita la informacion de un grupo, puede usarse para editar los permisos del mismo
  *
  *
  *
  **/

  class ApiPersonalRolEditar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, POST, array( "int" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"nombre" => new ApiExposedProperty("nombre", false, POST, array( "string" )),
			"salario" => new ApiExposedProperty("salario", false, POST, array( "float" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::EditarRol( 
 			
			
			isset($_POST['id_rol'] ) ? $_POST['id_rol'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['salario'] ) ? $_POST['salario'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
