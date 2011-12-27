<?php
/**
  * GET api/inventario/existencias
  * Obtener el catalogo de existencias del inventario.
  *
  * Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac? y lote.
Se puede ordenar por los atributos de producto. 
  *
  *
  *
  **/

  class ApiInventarioExistencias extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_almacen	" => new ApiExposedProperty("id_almacen	", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Existencias( 
 			
			
			isset($_GET['id_almacen	'] ) ? $_GET['id_almacen	'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
