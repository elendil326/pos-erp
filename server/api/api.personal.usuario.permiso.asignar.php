<?php
/**
  * POST api/personal/usuario/permiso/asignar
  * Asigna unpo varios permisos especificos a un usuario
  *
  * Asigna uno o varios permisos especificos a un usuario. No se pueden asignar permisos que ya se tienen
  *
  *
  *
  **/

  class ApiPersonalUsuarioPermisoAsignar extends ApiHandler {
  

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
 		$this->response = PersonalYAgentesController::AsignarPermisoUsuario( 
 			
			
			isset($_POST['id_permiso'] ) ? $_POST['id_permiso'] : null,
			isset($_POST['id_usuario'] ) ? $_POST['id_usuario'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
