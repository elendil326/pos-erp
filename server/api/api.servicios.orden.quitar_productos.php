<?php
/**
  * GET api/servicios/orden/quitar_productos
  * Retira productos de una orden de servicio
  *
  * Este metodo se usa para quitar productos de una orden de servicio. Puede ser usado para reducir su cantidad o para retirarlo por completo
  *
  *
  *
  **/

  class ApiServiciosOrdenQuitarProductos extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_orden_de_servicio" => new ApiExposedProperty("id_orden_de_servicio", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = ServiciosController::Quitar_productosOrden( 
 			
			
			isset($_GET['id_orden_de_servicio'] ) ? $_GET['id_orden_de_servicio'] : null,
			isset($_GET['productos'] ) ? json_decode($_GET['productos']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
