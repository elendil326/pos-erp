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
  

	protected function DeclareAllowedRoles()
	{
		return BYPASS; //
	}

	protected function CheckAuthorization() 
	{
		
	}

	protected function GetRequest()
	{
		var_dump( $_REQUEST );
		die();
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, POST, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"descuento" => new ApiExposedProperty("descuento", false, POST, array( "float" )),
			"salario" => new ApiExposedProperty("salario", false, POST, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		
		
		
		
		
	}
  }
  
  
  
  
  
  
