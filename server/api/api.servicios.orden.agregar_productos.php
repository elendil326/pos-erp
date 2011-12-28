<?php
/**
  * GET api/servicios/orden/agregar_productos
  * Agrega uno o varios productos a una orden de servicio
  *
  * En algunos servicios, se realiza la venta de productos de manera indirecta, por lo que se tiene que agregar a la orden de servicio. Este metodo puede ser usado apra agregar mas cantidad de un producto a uno ya existente, en este caso se ignoran los campos impuesto, descuento y retencion del arreglo de productos.
  *
  *
  *
  **/

  class ApiServiciosOrdenAgregarProductos extends ApiHandler {
  

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
 		$this->response = ServiciosController::Agregar_productosOrden( 
 			
			
			isset($_GET['id_orden_de_servicio'] ) ? $_GET['id_orden_de_servicio'] : null,
			isset($_GET['productos'] ) ? json_decode($_GET['productos']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
