<?php
/**
  * POST api/cliente/clasificacion/nueva
  * Crear una nueva clasificacion de cliente.
  *
  * Los cliente forzosamente pertenecen a una categoria. En base a esta categoria se calcula el precio que se le dara en una venta, o el descuento, o el credito.
  *
  *
  *
  **/

  class ApiClienteClasificacionNueva extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"clave_interna" => new ApiExposedProperty("clave_interna", true, POST, array( "string" )),
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ClientesController::NuevaClasificacion( 
 			
			
			isset($_POST['clave_interna'] ) ? $_POST['clave_interna'] : null,
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
