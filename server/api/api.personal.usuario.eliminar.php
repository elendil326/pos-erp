<?php
/**
  * POST api/personal/usuario/eliminar
  * Desactivar un usuario
  *
  * Este metodo desactiva un usuario, usese cuando un empleado ya no trabaje para usted. Que pasa cuando el usuario tiene cuentas abiertas o ventas a credito con saldo.
  *
  *
  *
  **/

  class ApiPersonalUsuarioEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, POST, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PersonalYAgentesController::EliminarUsuario( 
 			
			
			isset($_POST['id_usuario'] ) ? $_POST['id_usuario'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
