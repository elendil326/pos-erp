<?php
/**
  * POST api/personal/usuario/permiso/remover
  * Quita uno o varios permisos a un usuario
  *
  * Quita uno o varios permisos a un usuario. No se puede negar un permiso que no se tiene
  *
  *
  *
  **/

  class ApiPersonalUsuarioPermisoRemover extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_permiso" => new ApiExposedProperty("id_permiso", true, POST, array( "int" )),
			"id_usuario" => new ApiExposedProperty("id_usuario", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::RemoverPermisoUsuario( 
 			
			
			isset($_POST['id_permiso'] ) ? $_POST['id_permiso'] : null,
			isset($_POST['id_usuario'] ) ? $_POST['id_usuario'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
