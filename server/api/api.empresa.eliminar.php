<?php
/**
  * POST api/empresa/eliminar
  * Desactivar una empresa.
  *
  * Para poder eliminar una empresa es necesario que la empresa no tenga sucursales activas, sus saldos sean 0, que los clientes asociados a dicha empresa no tengan adeudo, ...
  *
  *
  *
  **/

  class ApiEmpresaEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", true, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EmpresasController::Eliminar( 
 			
			
			isset($_POST['id_empresa'] ) ? $_POST['id_empresa'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
