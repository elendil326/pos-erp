<?php
/**
  * GET api/precio/regla/lista
  * Lista las reglas de existentes
  *
  * Lista las reglas existentes. Puede filtrarse por la version, por producto, por unidad, por categoria de producto o servicio, por servicio o por paquete, por tarifa base o por alguna combinacion de ellos.
  *
  *
  *
  **/

  class ApiPrecioReglaLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_clasificacion_producto" => new ApiExposedProperty("id_clasificacion_producto", false, GET, array( "int" )),
			"id_clasificacion_servicio" => new ApiExposedProperty("id_clasificacion_servicio", false, GET, array( "int" )),
			"id_paquete" => new ApiExposedProperty("id_paquete", false, GET, array( "int" )),
			"id_producto" => new ApiExposedProperty("id_producto", false, GET, array( "int" )),
			"id_servicio" => new ApiExposedProperty("id_servicio", false, GET, array( "int" )),
			"id_tarifa" => new ApiExposedProperty("id_tarifa", false, GET, array( "int" )),
			"id_unidad" => new ApiExposedProperty("id_unidad", false, GET, array( "int" )),
			"id_version" => new ApiExposedProperty("id_version", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::ListaRegla( 
 			
			
			isset($_GET['id_clasificacion_producto'] ) ? $_GET['id_clasificacion_producto'] : null,
			isset($_GET['id_clasificacion_servicio'] ) ? $_GET['id_clasificacion_servicio'] : null,
			isset($_GET['id_paquete'] ) ? $_GET['id_paquete'] : null,
			isset($_GET['id_producto'] ) ? $_GET['id_producto'] : null,
			isset($_GET['id_servicio'] ) ? $_GET['id_servicio'] : null,
			isset($_GET['id_tarifa'] ) ? $_GET['id_tarifa'] : null,
			isset($_GET['id_unidad'] ) ? $_GET['id_unidad'] : null,
			isset($_GET['id_version'] ) ? $_GET['id_version'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
