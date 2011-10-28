<?php
/**
  * POST api/empresa/agregar_sucursales
  * Relacionar una sucursal a esta empresa. 
  *
  * Relacionar una sucursal a esta empresa. Cuando se llama a este metodo, se crea un almacen de esta sucursal para esta empresa
  *
  *
  *
  **/

  class ApiEmpresaAgregarSucursales extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_empresa" => new ApiExposedProperty("id_empresa", true, POST, array( "int" )),
			"sucursales" => new ApiExposedProperty("sucursales", true, POST, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = EmpresasController::Agregar_sucursales( 
 			
			
			isset($_POST['id_empresa'] ) ? $_POST['id_empresa'] : null,
			isset($_POST['sucursales'] ) ? $_POST['sucursales'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
