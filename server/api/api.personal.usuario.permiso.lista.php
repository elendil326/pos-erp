<?php
/**
  * GET api/personal/usuario/permiso/lista
  * Lista los permisos con sus usuarios
  *
  * Lista los permisos con los usuarios asigandos. Puede filtrarse por id_usuario o id_rol
  *
  *
  *
  **/

  class ApiPersonalUsuarioPermisoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_permiso" => new ApiExposedProperty("id_permiso", false, GET, array( "int" )),
			"id_usuario" => new ApiExposedProperty("id_usuario", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::ListaPermisoUsuario( 
 			
			
			isset($_GET['id_permiso'] ) ? $_GET['id_permiso'] : null,
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
