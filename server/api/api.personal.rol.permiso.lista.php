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
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::ListaPermisoRol( 
 			
		
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
