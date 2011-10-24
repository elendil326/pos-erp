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
			"existencia_mayor_que" => new ApiExposedProperty("existencia_mayor_que", false, GET, array( "float" )),
			"existencia_igual_que" => new ApiExposedProperty("existencia_igual_que", false, GET, array( "float" )),
			"existencia_menor_que" => new ApiExposedProperty("existencia_menor_que", false, GET, array( "float" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
			"id_almacen	" => new ApiExposedProperty("id_almacen	", false, GET, array( "int" )),
			"activo	" => new ApiExposedProperty("activo	", false, GET, array( "bool" )),
			"id_lote" => new ApiExposedProperty("id_lote", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Existencias( 
 			
			
			isset($_GET['existencia_mayor_que'] ) ? $_GET['existencia_mayor_que'] : null,
			isset($_GET['existencia_igual_que'] ) ? $_GET['existencia_igual_que'] : null,
			isset($_GET['existencia_menor_que'] ) ? $_GET['existencia_menor_que'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null,
			isset($_GET['id_almacen	'] ) ? $_GET['id_almacen	'] : null,
			isset($_GET['activo	'] ) ? $_GET['activo	'] : null,
			isset($_GET['id_lote'] ) ? $_GET['id_lote'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
