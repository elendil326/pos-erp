<?php
/**
  * GET api/precio/calcularPorArticulo
  * Calcula el precio de un producto
  *
  * Calcula el precio de un producto, servicio o paquete. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.

Se puede especificar una tarifa para calcular el precio del articulo solo en base a este.

Si no se recibe un producto o un servicio o un paquete se devuelve un error

Se sigue la jerarquia 1-id_producto,2-id_servicio,3-id_paquete por si se recibe mas de uno de estos parametros. Por ejemplo si se recibe un id_producto y un id_paquete, el id_paquete sera ignorado y se calculara solamente el precio del producto
  *
  *
  *
  **/

  class ApiPrecioCalcularPorArticulo extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"tipo_tarifa" => new ApiExposedProperty("tipo_tarifa", true, GET, array( "string" )),
			"cantidad" => new ApiExposedProperty("cantidad", false, GET, array( "float" )),
			"id_paquete" => new ApiExposedProperty("id_paquete", false, GET, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, GET, array( "int" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", false, GET, array( "int" )),
			"id_tarifa" => new ApiExposedProperty("id_tarifa", false, GET, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::CalcularPorArticulo( 
 			
			
			isset($_GET['tipo_tarifa'] ) ? $_GET['tipo_tarifa'] : null,
			isset($_GET['cantidad'] ) ? $_GET['cantidad'] : null,
			isset($_GET['id_paquete'] ) ? $_GET['id_paquete'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['id_servicio'] ) ? $_GET['id_servicio'] : null,
			isset($_GET['id_tarifa'] ) ? $_GET['id_tarifa'] : null,
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
