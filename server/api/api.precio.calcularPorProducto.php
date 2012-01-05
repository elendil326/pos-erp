<?php
/**
  * GET api/precio/calcularPorProducto
  * Calcula el precio de un producto
  *
  * Calcula el precio de un producto. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.
  *
  *
  *
  **/

  class ApiPrecioCalcularPorProducto extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_producto" => new ApiExposedProperty("id_producto", true, GET, array( "int" )),
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", true, GET, array( "string" )),
			"cantidad" => new ApiExposedProperty("cantidad", false, GET, array( "float" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::CalcularPorProducto( 
 			
			
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['tipo_tarifa'] ) ? $_GET['tipo_tarifa'] : null,
			isset($_GET['cantidad'] ) ? $_GET['cantidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
