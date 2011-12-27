<?php
/**
  * GET api/personal/rol/permiso/lista
  * Listar los permisos del API
  *
  * Regresa un alista de permisos, nombres y ids de los permisos del sistema.
  *
  *
  *
  **/

  class ApiPersonalRolPermisoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_permiso" => new ApiExposedProperty("id_permiso", false, GET, array( "int" )),
			"id_rol" => new ApiExposedProperty("id_rol", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::ListaPermisoRol( 
 			
			
			isset($_GET['id_permiso'] ) ? $_GET['id_permiso'] : null,
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
