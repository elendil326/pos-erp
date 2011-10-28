<?php
/**
  * POST api/sucursal/almacen/nuevo
  * Crear un nuevo almacen en una sucursal
  *
  * Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
  *
  *
  *
  **/

  class ApiSucursalAlmacenNuevo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"nombre" => new ApiExposedProperty("nombre", true, POST, array( "string" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", true, POST, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", true, POST, array( "int" )),
			"id_tipo_almacen" => new ApiExposedProperty("id_tipo_almacen", true, POST, array( "int" )),
			"descripcion" => new ApiExposedProperty("descripcion", false, POST, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::NuevoAlmacen( 
 			
			
			isset($_POST['nombre'] ) ? $_POST['nombre'] : null,
			isset($_POST['id_sucursal'] ) ? $_POST['id_sucursal'] : null,
			isset($_POST['id_empresa'] ) ? $_POST['id_empresa'] : null,
			isset($_POST['id_tipo_almacen'] ) ? $_POST['id_tipo_almacen'] : null,
			isset($_POST['descripcion'] ) ? $_POST['descripcion'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
