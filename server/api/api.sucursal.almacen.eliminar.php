<?php
/**
  * GET api/sucursal/almacen/eliminar
  * Elimina un almacen
  *
  * Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?o
  *
  *
  *
  **/

  class ApiSucursalAlmacenEliminar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_almacen" => new ApiExposedProperty("id_almacen", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SucursalesController::EliminarAlmacen( 
 			
			
			isset($_GET['id_almacen'] ) ? $_GET['id_almacen'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
