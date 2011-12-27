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
			"compra_en_mostrador" => new ApiExposedProperty("compra_en_mostrador", false, GET, array( "bool" )),
			"id_almacen" => new ApiExposedProperty("id_almacen", false, GET, array( "int" )),
			"id_empresa" => new ApiExposedProperty("id_empresa", false, GET, array( "int" )),
			"metodo_costeo" => new ApiExposedProperty("metodo_costeo", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ProductosController::Lista( 
 			
			
			isset($_GET['activo'] ) ? $_GET['activo'] : null,
			isset($_GET['compra_en_mostrador'] ) ? $_GET['compra_en_mostrador'] : null,
			isset($_GET['id_almacen'] ) ? $_GET['id_almacen'] : null,
			isset($_GET['id_empresa'] ) ? $_GET['id_empresa'] : null,
			isset($_GET['metodo_costeo'] ) ? $_GET['metodo_costeo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
