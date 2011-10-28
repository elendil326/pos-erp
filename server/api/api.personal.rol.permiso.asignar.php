<?php
/**
  * POST api/personal/rol/permiso/asignar
  * Asigna permisos a un rol
  *
  * Este metodo asigna permisos a un rol. Cada vez que se llame a este metodo, se asignaran estos permisos a los usuarios que pertenezcan a este rol.
  *
  *
  *
  **/

  class ApiPersonalRolPermisoAsignar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_permiso" => new ApiExposedProperty("id_permiso", true, POST, array( "int" )),
			"id_rol" => new ApiExposedProperty("id_rol", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::AsignarPermisoRol( 
 			
			
			isset($_POST['id_permiso'] ) ? $_POST['id_permiso'] : null,
			isset($_POST['id_rol'] ) ? $_POST['id_rol'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
