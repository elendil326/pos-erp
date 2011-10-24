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
			"id_lote_nuevo" => new ApiExposedProperty("id_lote_nuevo", true, GET, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", true, GET, array( "int" )),
			"id_lote_viejo" => new ApiExposedProperty("id_lote_viejo", true, GET, array( "int" )),
			"cantidad" => new ApiExposedProperty("cantidad", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = InventarioController::Procesar_producto( 
 			
			
			isset($_GET['id_lote_nuevo'] ) ? $_GET['id_lote_nuevo'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['id_lote_viejo'] ) ? $_GET['id_lote_viejo'] : null,
			isset($_GET['cantidad'] ) ? $_GET['cantidad'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
