<?php
/**
  * POST api/sucursal/tipo_almacen/nuevo
  * Crea un nuevo tipo de almacen
  *
  * Crea un nuevo tipo de almacen
  *
  *
  *
  **/

  class ApiSucursalTipoAlmacenNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"descripcion" => new ApiExposedProperty("descripcion", true, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::NuevoTipo_almacen( 
 			
			
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
