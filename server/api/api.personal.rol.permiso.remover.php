<?php
/**
  * POST api/personal/rol/permiso/remover
  * Quita uno o varios permisos a un rol
  *
  * Este metodo quita un permiso de un rol. Al remover este permiso de un rol, los permisos que un usuario especifico tiene gracias a una asignacion especial se mantienen. 
  *
  *
  *
  **/

  class ApiPersonalRolPermisoRemover extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_permiso" => new ApiExposedProperty("id_permiso", true, POST, array( "int" )),
			"id_rol" => new ApiExposedProperty("id_rol", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::RemoverPermisoRol( 
 			
			
			isset($_POST['id_permiso'] ) ? $_POST['id_permiso'] : null,
			isset($_POST['id_rol'] ) ? $_POST['id_rol'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
