<?php
/**
  * POST api/personal/rol/nuevo
  * Crea un nuevo grupo de usuarios
  *
  * Crea un nuevo grupo de usuarios. Se asignaran los permisos de este grupo al momento de su creacion.
  *
  *
  *
  **/
  

  class ApiPersonalRolNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){ return BYPASS; }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, POST, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"salario" => new ApiExposedProperty("salario", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		$this->response = PersonalYAgentesController::NuevoRol( 
 			
			
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['descuento'] ) ? $_POST['descuento'] : null,
			isset($_POST['salario'] ) ? $_POST['salario'] : null
			
			);
	}
  }
  
  
  
  
  
  
