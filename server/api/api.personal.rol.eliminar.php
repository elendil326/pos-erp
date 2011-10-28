<?php
/**
  * POST api/personal/rol/eliminar
  * Elimina un grupo
  *
  * Este metodo desactiva un grupo, solo se podra desactivar un grupo si no hay ningun usuario que pertenezca a ?l.
  *
  *
  *
  **/

  class ApiPersonalRolEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::EliminarRol( 
 			
			
			isset($_POST['id_rol'] ) ? $_POST['id_rol'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
