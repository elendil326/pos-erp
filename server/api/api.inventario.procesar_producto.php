<?php
/**
  * GET api/inventario/procesar_producto
  * Procesar producto no es mas que moverlo de lote.
  *
  * Procesar producto no es mas que moverlo de lote.
  *
  *
  *
  **/

  class ApiInventarioProcesarProducto extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"cantidad_nueva" => new ApiExposedProperty("cantidad_nueva", true, GET, array( "float" )),
			"cantidad_vieja" => new ApiExposedProperty("cantidad_vieja", true, GET, array( "float" )),
			"id_almacen_nuevo" => new ApiExposedProperty("id_almacen_nuevo", true, GET, array( "int" )),
			"id_almacen_viejo" => new ApiExposedProperty("id_almacen_viejo", true, GET, array( "int" )),
			"id_producto_nuevo" => new ApiExposedProperty("id_producto_nuevo", true, GET, array( "int" )),
			"id_producto_viejo" => new ApiExposedProperty("id_producto_viejo", true, GET, array( "int" )),
			"id_unidad_nueva" => new ApiExposedProperty("id_unidad_nueva", true, GET, array( "int" )),
			"id_unidad_vieja" => new ApiExposedProperty("id_unidad_vieja", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Procesar_producto( 
 			
			
			isset($_GET['cantidad_nueva'] ) ? $_GET['cantidad_nueva'] : null,
			isset($_GET['cantidad_vieja'] ) ? $_GET['cantidad_vieja'] : null,
			isset($_GET['id_almacen_nuevo'] ) ? $_GET['id_almacen_nuevo'] : null,
			isset($_GET['id_almacen_viejo'] ) ? $_GET['id_almacen_viejo'] : null,
			isset($_GET['id_producto_nuevo'] ) ? $_GET['id_producto_nuevo'] : null,
			isset($_GET['id_producto_viejo'] ) ? $_GET['id_producto_viejo'] : null,
			isset($_GET['id_unidad_nueva'] ) ? $_GET['id_unidad_nueva'] : null,
			isset($_GET['id_unidad_vieja'] ) ? $_GET['id_unidad_vieja'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
