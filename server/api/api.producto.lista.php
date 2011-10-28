<?php
/**
  * GET api/producto/lista
  * Obtener la lista de productos.
  *
  * Se puede ordenar por los atributos de producto. 
  *
  *
  *
  **/

  class ApiProductoLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"activo" => new ApiExposedProperty("activo", false, GET, array( "bool" )),
			"id_lote" => new ApiExposedProperty("id_lote", false, GET, array( "int" )),
			"id_almacen" => new ApiExposedProperty("id_almacen", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"id_sucursal" => new ApiExposedProperty("id_sucursal", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Lista( 
 			
			
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['id_lote'] ) ? $_GET['id_lote'] : null,
			isset($_GET['id_almacen'] ) ? $_GET['id_almacen'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['id_sucursal'] ) ? $_GET['id_sucursal'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
